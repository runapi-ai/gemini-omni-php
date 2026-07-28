<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\GeminiOmni\Models\CreateAudioResponse;

/** Create audio operations for Gemini Omni. */
readonly class CreateAudio extends SyncResource
{
    /**
     * Run create audio and return its response.
     *
     * @param array{
     *   audio_id: string,
     *   model: string,
     *   name: string,
     *   example_dialogue?: string,
     *   voice_description?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CreateAudioResponse
    {
        $response = parent::run($params, $options);

        /** @var CreateAudioResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/gemini_omni/create_audio',
            'gemini-omni/create-audio',
            CreateAudioResponse::class,
        );
    }
}
