<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\GeminiOmni\GeminiOmniClient;
use RunApi\GeminiOmni\Models\CompletedVideoTaskResponse;
use RunApi\GeminiOmni\Models\CreateAudioResponse;
use RunApi\GeminiOmni\Models\CreateCharacterResponse;
use RunApi\GeminiOmni\Resources\CreateAudio;
use RunApi\GeminiOmni\Resources\CreateCharacter;
use RunApi\GeminiOmni\Resources\TextToVideo;

final class GeminiOmniClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToVideo::class, $client->textToVideo);
        self::assertInstanceOf(CreateAudio::class, $client->createAudio);
        self::assertInstanceOf(CreateCharacter::class, $client->createCharacter);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToVideo->create([
            'model' => 'gemini-omni-text-to-video',
            'prompt' => 'A product render',
            'duration_seconds' => 4,
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'audio_ids' => ['audio_1'],
            'video_list' => [['url' => 'https://cdn.runapi.ai/public/samples/video.mp4', 'start' => 0, 'ends' => 4]],
            'character_ids' => ['character_1'],
            'aspect_ratio' => '16:9',
            'output_resolution' => '720p',
            'seed' => 1,
            'callback_url' => '',
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/gemini_omni/text_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('gemini-omni-text-to-video', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"generation_stage":"all_audios_ready","extra_field":"kept"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToVideo->run([
            'model' => 'gemini-omni-text-to-video',
            'prompt' => 'A product render',
            'duration_seconds' => 4,
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'audio_ids' => ['audio_1'],
            'video_list' => [['url' => 'https://cdn.runapi.ai/public/samples/video.mp4', 'start' => 0, 'ends' => 4]],
            'character_ids' => ['character_1'],
            'aspect_ratio' => '16:9',
            'output_resolution' => '720p',
            'seed' => 1,
        ]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/gemini_omni/text_to_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('videos is required');

        $client->textToVideo->run([
            'model' => 'gemini-omni-text-to-video',
            'prompt' => 'A product render',
            'duration_seconds' => 4,
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'audio_ids' => ['audio_1'],
            'video_list' => [['url' => 'https://cdn.runapi.ai/public/samples/video.mp4', 'start' => 0, 'ends' => 4]],
            'character_ids' => ['character_1'],
            'aspect_ratio' => '16:9',
            'output_resolution' => '720p',
            'seed' => 1,
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('aspect_ratio must be one of the allowed values');

        $client->textToVideo->create([
        'model' => 'gemini-omni-flash-preview',
        'prompt' => 'A product render',
        'output_resolution' => '720p',
        'aspect_ratio' => 'not-valid',
        ]);
    }
    public function testCreateAudioRunsSynchronously(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"audio":{"id":"voice_1","name":"Narrator"},"billing":{"reservation":{"amount_cents":12}},"id":"sync_audio"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->createAudio->run([
        'model' => 'gemini-omni-audio',
        'audio_id' => 'zephyr',
        'name' => 'sample',
        'voice_description' => 'sample',
        'example_dialogue' => 'sample',
        ]);

        self::assertInstanceOf(CreateAudioResponse::class, $result);
        self::assertSame('voice_1', $result->audio?->id);
        self::assertSame(12, $result->billing?->reservation?->amountCents);
        self::assertSame('/api/v1/gemini_omni/create_audio', $transport->requests[0]->getUri()->getPath());
    }
    public function testCreateCharacterRunsSynchronously(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"character":{"id":"char_1","name":"Guide"},"billing":{"refund":{"refunded_at":"2026-07-23T12:00:00.000000Z"}},"id":"sync_character"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->createCharacter->run([
        'model' => 'gemini-omni-character',
        'descriptions' => 'A friendly narrator wearing a blue jacket',
        'reference_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        'audio_ids' => ['audio_1'],
        'character_name' => 'Narrator',
        ]);

        self::assertInstanceOf(CreateCharacterResponse::class, $result);
        self::assertSame('char_1', $result->character?->id);
        self::assertSame('2026-07-23T12:00:00.000000Z', $result->billing?->refund?->refundedAt);
        self::assertSame('/api/v1/gemini_omni/create_character', $transport->requests[0]->getUri()->getPath());
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"audio":{"id":"voice_1","name":"Narrator"},"billing":{"reservation":{"amount_cents":12}},"id":"sync_audio"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->createAudio->run([
            'model' => 'gemini-omni-audio',
            'audio_id' => 'zephyr',
            'name' => 'sample',
            'voice_description' => 'sample',
            'example_dialogue' => 'sample',
        ]);

        self::assertSame('/api/v1/gemini_omni/create_audio', $transport->requests[0]->getUri()->getPath());
    }
}
