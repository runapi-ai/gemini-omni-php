<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/**
 * Async create character response task response with lifecycle status and output files.
 */
readonly class CreateCharacterResponse extends BaseModel
{
    /**
     * Create a character creation response value object.
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(public string $id, public ?GeminiOmniCharacter $character = null, public ?string $error = null, array $raw = [])
    {
        parent::__construct($raw === [] ? ['id' => $id, 'character' => $character?->toArray(), 'error' => $error] : $raw);
    }

    /**
     * Hydrate a character creation response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), character: self::character($raw), error: self::error($raw), raw: $raw);
    }

    /** @param array<string, mixed> $raw */
    private static function character(array $raw): ?GeminiOmniCharacter
    {
        $value = $raw['character'] ?? null;
        if ($value === null) {
            return null;
        }
        if (!is_array($value)) {
            throw new ValidationException('character must be an object');
        }

        /** @var array<string, mixed> $value */
        return GeminiOmniCharacter::fromArray($value);
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
