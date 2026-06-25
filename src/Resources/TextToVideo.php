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

/**
 * Generates video from a prompt with optional characters, audio voices, reference images, and video clips. This is async -- use create()/get() for manual polling, or run() for automatic polling.
 */
readonly class TextToVideo extends TypedConfiguredResource
{
    /**
     * Submits a Gemini Omni text-to-video task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   duration_seconds?: int,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a Gemini Omni text-to-video task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a Gemini Omni text-to-video task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   duration_seconds?: int,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
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
