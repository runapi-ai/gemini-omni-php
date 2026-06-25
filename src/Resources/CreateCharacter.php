<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Contract\ContractValidator;
use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\GeminiOmni\Models\CreateCharacterResponse;

/**
 * Builds a reusable character from a reference image and description. Attach audio_ids to give the character a specific voice. This is synchronous -- only run() is available.
 */
readonly class CreateCharacter
{
    private const ENDPOINT = '/api/v1/gemini_omni/create_character';
    private const ACTION = 'gemini-omni/create-character';

    /**
     * Create a resource using the shared RunAPI HTTP transport.
     */
    public function __construct(private HttpClient $http, private ContractValidator $validator = new ContractValidator())
    {
    }

    /**
     * Submits a Gemini Omni character creation task and returns the result.
     *
     * @param array{
     *   model: string,
     *   reference_image_url: string,
     *   descriptions?: string,
     *   audio_ids?: list<string>,
     *   character_name?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CreateCharacterResponse
    {
        $params = $this->compact($params);
        $model = $this->model($params);
        $this->validator->validate(self::ACTION, $model, $params);

        return CreateCharacterResponse::fromArray($this->http->request('post', self::ENDPOINT, [
            'body' => $params,
            'options' => $options,
        ]));
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self($http);
    }

    /** @param array<string, mixed> $params */
    private function model(array $params): string
    {
        $model = $params['model'] ?? null;

        return is_string($model) && $model !== '' ? $model : '_';
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function compact(array $params): array
    {
        $result = [];
        foreach ($params as $key => $value) {
            if ($value === null || $value === '' || (is_array($value) && $value === [])) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
