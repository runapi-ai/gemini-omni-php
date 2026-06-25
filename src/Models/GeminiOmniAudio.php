<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/**
 * Gemini omni audio response model.
 */
readonly class GeminiOmniAudio extends BaseModel
{
    /**
     * Create a Gemini Omni audio value object.
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(public string $id, public ?string $name = null, array $raw = [])
    {
        parent::__construct($raw === [] ? ['id' => $id, 'name' => $name] : $raw);
    }

    /**
     * Hydrate Gemini Omni audio metadata from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        $name = $raw['name'] ?? null;

        return new self(
            id: Payload::string($raw, 'id'),
            name: is_string($name) ? $name : null,
            raw: $raw,
        );
    }
}
