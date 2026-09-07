<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni;

final class Types
{
    public const MODEL_FLASH_1_1 = GeneratedModels::TEXT_TO_VIDEO_GEMINI_OMNI_FLASH_1_1;
    public const MODEL_FLASH_PREVIEW = GeneratedModels::TEXT_TO_VIDEO_GEMINI_OMNI_FLASH_PREVIEW;
    public const MODEL_TEXT_TO_VIDEO = GeneratedModels::TEXT_TO_VIDEO_GEMINI_OMNI_TEXT_TO_VIDEO;

    /**
     * Allowed model slugs for text to video requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_VIDEO_MODELS = [self::MODEL_FLASH_1_1, self::MODEL_FLASH_PREVIEW, self::MODEL_TEXT_TO_VIDEO];

    /**
     * Allowed model slugs for create audio requests.
     *
     * @var list<string>
     */
    public const CREATE_AUDIO_MODELS = ['gemini-omni-audio'];

    /**
     * Allowed model slugs for create character requests.
     *
     * @var list<string>
     */
    public const CREATE_CHARACTER_MODELS = ['gemini-omni-character'];

    private function __construct()
    {
    }
}
