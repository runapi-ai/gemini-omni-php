<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Resources;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\BaseModel;
use RunApi\Core\RequestOptions;

/** Shared synchronous request boundary for Gemini Omni helper resources. */
abstract readonly class SyncResource
{
    /** @param class-string<BaseModel> $responseClass */
    public function __construct(
        protected HttpClient $http,
        private string $endpoint,
        private string $responseClass,
    ) {
    }

    /** @param array<string, mixed> $params */
    public function run(array $params, ?RequestOptions $options = null): BaseModel
    {
        $factory = [$this->responseClass, 'fromArray'];
        if (!is_callable($factory)) {
            throw new ValidationException($this->responseClass . ' must define fromArray');
        }

        $response = $factory($this->http->request('post', $this->endpoint, [
            'body' => $this->compact($params),
            'options' => $options,
        ]));
        if (!$response instanceof BaseModel) {
            throw new ValidationException($this->responseClass . ' must return a BaseModel');
        }

        return $response;
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    private function compact(array $params): array
    {
        return array_filter($params, static fn (mixed $value): bool => $value !== null && $value !== '' && $value !== []);
    }
}
