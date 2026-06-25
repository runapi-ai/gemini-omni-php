<?php

declare(strict_types=1);

namespace RunApi\GeminiOmni;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\GeminiOmni\Resources\CreateAudio;
use RunApi\GeminiOmni\Resources\CreateCharacter;
use RunApi\GeminiOmni\Resources\TextToVideo;

/**
 * Provides Gemini Omni multimodal generation: voice presets, character creation, and text-to-video.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class GeminiOmniClient extends BaseClient
{
    /**
     * Text to video operations.
     */
    public readonly TextToVideo $textToVideo;
    /**
     * Create audio operations.
     */
    public readonly CreateAudio $createAudio;
    /**
     * Create character operations.
     */
    public readonly CreateCharacter $createCharacter;

    /**
     * Create a Gemini Omni client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToVideo = TextToVideo::fromHttp($this->http);
        $this->createAudio = CreateAudio::fromHttp($this->http);
        $this->createCharacter = CreateCharacter::fromHttp($this->http);
    }
}
