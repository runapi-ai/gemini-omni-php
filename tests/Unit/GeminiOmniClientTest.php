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
            'aspect_ratio' => '16:9',
            'duration_seconds' => 4,
            'output_resolution' => '720p',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/gemini_omni/text_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('gemini-omni-text-to-video', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToVideo->run([
            'model' => 'gemini-omni-text-to-video',
            'aspect_ratio' => '16:9',
            'duration_seconds' => 4,
            'output_resolution' => '720p',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/gemini_omni/text_to_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testFlashPreviewSendsModelWithoutDuration(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_flash","status":"processing"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToVideo->create([
            'model' => 'gemini-omni-flash-preview',
            'prompt' => 'A paper airplane flying through a sunlit studio',
            'aspect_ratio' => '9:16',
            'output_resolution' => '720p',
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('gemini-omni-flash-preview', $body['model']);
        self::assertArrayNotHasKey('duration_seconds', $body);
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
            'aspect_ratio' => '16:9',
            'duration_seconds' => 4,
            'output_resolution' => '720p',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('aspect_ratio must be one of the allowed values');

        $client->textToVideo->create([
        'model' => 'gemini-omni-text-to-video',
        'duration_seconds' => 4,
        'output_resolution' => '720p',
        'prompt' => 'A product render',
        'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        'aspect_ratio' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"audio_1","audio":{"id":"voice_1","name":"Narrator"},"extra_field":"kept"}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->createAudio->run([
            'model' => 'gemini-omni-audio',
            'audio_id' => 'zephyr',
            'name' => 'Narrator',
            'voice_description' => '',
        ]);
        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertInstanceOf(CreateAudioResponse::class, $result);
        self::assertSame('voice_1', $result->audio->id);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/gemini_omni/create_audio', $transport->requests[0]->getUri()->getPath());
        self::assertSame('gemini-omni-audio', $body['model']);
        self::assertArrayNotHasKey('voice_description', $body);
    }

    public function testCreateCharacterRunReturnsTypedResponse(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"character_1","character":{"id":"char_1","name":"Guide","images":[{"url":"https://cdn.runapi.ai/public/samples/reference-1.jpg"}]}}'),
        ]);
        $client = new GeminiOmniClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->createCharacter->run([
            'model' => 'gemini-omni-character',
            'descriptions' => 'A friendly guide in a blue jacket',
            'reference_image_url' => 'https://cdn.runapi.ai/public/samples/reference-1.jpg',
            'audio_ids' => ['voice_1'],
            'character_name' => 'Guide',
        ]);

        self::assertInstanceOf(CreateCharacterResponse::class, $result);
        self::assertSame('char_1', $result->character->id);
        self::assertSame('https://cdn.runapi.ai/public/samples/reference-1.jpg', $result->character->images[0]->url);
        self::assertSame('/api/v1/gemini_omni/create_character', $transport->requests[0]->getUri()->getPath());
    }
}
