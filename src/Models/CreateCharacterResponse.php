<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\BaseModel;
use RunApi\Core\Models\TaskBillingFacts;
use RunApi\Core\Support\Payload;

/** Response returned by create character. */
readonly class CreateCharacterResponse extends BaseModel
{
    public ?TaskBillingFacts $billing;

    /**
     * @param array<string, mixed> $raw
     */
    public function __construct(public string $id, public ?GeminiOmniCharacter $character = null, public ?string $error = null, array $raw = [], ?TaskBillingFacts $billing = null)
    {
        $this->billing = $billing ?? self::billing($raw);
        parent::__construct($raw === [] ? ['id' => $id, 'character' => $character?->toArray(), 'error' => $error, 'billing' => $this->billing?->toArray()] : $raw);
    }

    /**
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

    /** @param array<string, mixed> $raw */
    private static function billing(array $raw): ?TaskBillingFacts
    {
        return isset($raw['billing']) && is_array($raw['billing']) ? TaskBillingFacts::fromArray($raw['billing']) : null;
    }
}
