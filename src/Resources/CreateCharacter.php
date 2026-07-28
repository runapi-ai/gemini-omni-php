<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\GeminiOmni\Models\CreateCharacterResponse;

/** Create character operations for Gemini Omni. */
readonly class CreateCharacter extends SyncResource
{
    /**
     * Run create character and return its response.
     *
     * @param array{
     *   descriptions: string,
     *   model: string,
     *   reference_image_url: string,
     *   audio_ids?: list<string>,
     *   character_name?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CreateCharacterResponse
    {
        $response = parent::run($params, $options);

        /** @var CreateCharacterResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/gemini_omni/create_character',
            'gemini-omni/create-character',
            CreateCharacterResponse::class,
        );
    }
}
