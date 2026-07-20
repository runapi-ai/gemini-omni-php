<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni;

/**
 * Constants for model slugs supported by the Gemini Omni PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_VIDEO_MODELS = ['gemini-omni-flash-preview', 'gemini-omni-text-to-video'];

    /** @var list<string> */
    public const CREATE_AUDIO_MODELS = ['gemini-omni-audio'];

    /** @var list<string> */
    public const CREATE_CHARACTER_MODELS = ['gemini-omni-character'];

    private function __construct()
    {
    }
}
