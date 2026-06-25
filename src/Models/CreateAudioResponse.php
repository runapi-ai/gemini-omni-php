<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/**
 * Async audio task response with lifecycle status and output files.
 */
readonly class CreateAudioResponse extends BaseModel
{
    /**
     * Create an audio creation response value object.
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(public string $id, public ?GeminiOmniAudio $audio = null, public ?string $error = null, array $raw = [])
    {
        parent::__construct($raw === [] ? ['id' => $id, 'audio' => $audio?->toArray(), 'error' => $error] : $raw);
    }

    /**
     * Hydrate an audio creation response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), audio: self::audio($raw), error: self::error($raw), raw: $raw);
    }

    /** @param array<string, mixed> $raw */
    private static function audio(array $raw): ?GeminiOmniAudio
    {
        $value = $raw['audio'] ?? null;
        if ($value === null) {
            return null;
        }
        if (!is_array($value)) {
            throw new ValidationException('audio must be an object');
        }

        /** @var array<string, mixed> $value */
        return GeminiOmniAudio::fromArray($value);
    }

    /** @param array<string, mixed> $raw */
    private static function error(array $raw): ?string
    {
        $value = $raw['error'] ?? null;
        if ($value === null) {
            return null;
        }
        if (!is_string($value)) {
            throw new ValidationException('error must be a string');
        }

        return $value;
    }
}
