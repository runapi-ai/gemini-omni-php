<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/**
 * Gemini omni character response model.
 */
readonly class GeminiOmniCharacter extends BaseModel
{
    /**
     * Create a Gemini Omni character value object.
     *
     * @param list<ImageMetadata> $images
     * @param array<string, mixed> $raw
     */
    public function __construct(public string $id, public ?string $name = null, public array $images = [], array $raw = [])
    {
        parent::__construct($raw === [] ? ['id' => $id, 'name' => $name, 'images' => array_map(static fn (ImageMetadata $image): array => $image->toArray(), $images)] : $raw);
    }

    /**
     * Hydrate Gemini Omni character metadata from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        $name = $raw['name'] ?? null;

        return new self(
            id: Payload::string($raw, 'id'),
            name: is_string($name) ? $name : null,
            images: self::images($raw),
            raw: $raw,
        );
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<ImageMetadata>
     */
    private static function images(array $raw): array
    {
        $value = $raw['images'] ?? null;
        if ($value === null) {
            return [];
        }
        if (!is_array($value)) {
            throw new ValidationException('images must be an array');
        }

        $images = [];
        foreach ($value as $index => $item) {
            if (!is_array($item)) {
                throw new ValidationException('images[' . $index . '] must be an object');
            }
            /** @var array<string, mixed> $item */
            $images[] = ImageMetadata::fromArray($item);
        }

        return $images;
    }
}
