<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Contract\ContractValidator;
use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\GeminiOmni\Models\CreateAudioResponse;

/**
 * Registers a reusable voice preset from a built-in voice identity. This is synchronous -- only run() is available (no create()/get() polling).
 */
readonly class CreateAudio
{
    private const ENDPOINT = '/api/v1/gemini_omni/create_audio';
    private const ACTION = 'gemini-omni/create-audio';

    /**
     * Create a resource using the shared RunAPI HTTP transport.
     */
    public function __construct(private HttpClient $http, private ContractValidator $validator = new ContractValidator())
    {
    }

    /**
     * Submits a Gemini Omni audio creation task and returns the result.
     *
     * @param array{
     *   model: string,
     *   audio_id?: string,
     *   name?: string,
     *   voice_description?: string,
     *   example_dialogue?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CreateAudioResponse
    {
        $params = $this->compact($params);
        $model = $this->model($params);
        $this->validator->validate(self::ACTION, $model, $params);

        return CreateAudioResponse::fromArray($this->http->request('post', self::ENDPOINT, [
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
