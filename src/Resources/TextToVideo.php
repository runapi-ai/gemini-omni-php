<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\GeminiOmni\Models\CompletedVideoTaskResponse;
use RunApi\GeminiOmni\Models\VideoTaskResponse;
use RunApi\GeminiOmni\Types;

/** Text to video operations for Gemini Omni. */
readonly class TextToVideo extends TypedConfiguredResource
{
    /**
     * Create a text to video task and return immediately with a task id.
     *
     * @param array{
     *   prompt: string,
     *   aspect_ratio?: string,
     *   audio_ids?: list<string>,
     *   callback_url?: string,
     *   character_ids?: list<string>,
     *   duration_seconds?: int,
     *   model?: string,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>,
     *   seed?: int,
     *   video_list?: list<array{url: string, start: int, ends: int}>
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a text to video task. */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Create a text to video task and poll until it completes.
     *
     * @param array{
     *   prompt: string,
     *   aspect_ratio?: string,
     *   audio_ids?: list<string>,
     *   callback_url?: string,
     *   character_ids?: list<string>,
     *   duration_seconds?: int,
     *   model?: string,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>,
     *   seed?: int,
     *   video_list?: list<array{url: string, start: int, ends: int}>
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/gemini_omni/text_to_video',
            'gemini-omni/text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::TEXT_TO_VIDEO_MODELS,
            'text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
