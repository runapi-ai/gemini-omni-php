<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/**
 * Image metadata response model.
 */
readonly class ImageMetadata extends BaseModel
{
    /**
     * Create an image metadata value object.
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(public string $url, array $raw = [])
    {
        parent::__construct($raw === [] ? ['url' => $url] : $raw);
    }

    /**
     * Hydrate an image metadata from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(url: Payload::string($raw, 'url'), raw: $raw);
    }
}
