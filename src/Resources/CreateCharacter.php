<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\HybridResource;
use RunApi\GeminiOmni\Models\CreateCharacterResponse;

/** Create a Gemini Omni character from a portrait and optional full-body reference. */
readonly class CreateCharacter extends HybridResource
{
    /**
     * Create a character and return its terminal response.
     *
     * @param array{
     *   descriptions: string,
     *   model: string,
     *   reference_image_url: string,
     *   body_reference_image_url?: string,
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
            CreateCharacterResponse::class,
        );
    }
}
