<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni;

final class Types
{
    /**
     * Allowed model slugs for text to video requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_VIDEO_MODELS = ['gemini-omni-flash-preview', 'gemini-omni-text-to-video'];

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
