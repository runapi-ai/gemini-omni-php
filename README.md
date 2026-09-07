# Gemini Omni PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/gemini-omni)](https://packagist.org/packages/runapi-ai/gemini-omni)
[![License](https://img.shields.io/github/license/runapi-ai/gemini-omni-php)](https://github.com/runapi-ai/gemini-omni-php/blob/main/LICENSE)

The Gemini Omni PHP SDK is the language-specific package for Gemini Omni
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `gemini-omni-php` split
repository. For model details, use https://runapi.ai/models/gemini-omni; for API
reference, use https://runapi.ai/docs/api/gemini-omni/text-to-video; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

## Install

```bash
composer require runapi-ai/gemini-omni
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\GeminiOmni\GeminiOmniClient;

$client = new GeminiOmniClient(); // reads RUNAPI_API_KEY

$createAudioResult = $client->createAudio->run([
    'model' => 'gemini-omni-audio',
    'audio_id' => 'zephyr',
    'example_dialogue' => 'sample',
    'name' => 'sample',
    'voice_description' => 'sample',
]);

$character = $client->createCharacter->run([
    'descriptions' => 'A silver-haired cyberpunk guide',
    'reference_image_url' => 'https://cdn.runapi.ai/public/samples/portrait.jpg',
    'body_reference_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
]);

$task = $client->textToVideo->create([
    'model' => 'gemini-omni-flash-1-1',
    'aspect_ratio' => '16:9',
    'duration_seconds' => 6,
    'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/first-frame.jpg',
    'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/last-frame.jpg',
    'output_resolution' => '360p',
    'prompt' => 'A precise product render travels from dawn into dusk',
]);

$status = $client->textToVideo->get($task->id);

$result = $client->textToVideo->run([
    'model' => 'gemini-omni-flash-1-1',
    'aspect_ratio' => '16:9',
    'duration_seconds' => 6,
    'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/first-frame.jpg',
    'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/last-frame.jpg',
    'output_resolution' => '360p',
    'prompt' => 'A serene mountain lake travels from dawn into dusk',
]);

echo $result->videos[0]->url . PHP_EOL;
```

`$character->character->images` is ordered portrait first and optional full-body image second. A character created with both references consumes two of the seven reference units in a multimodal video request.

For `gemini-omni-flash-1-1`, `first_frame_image_url` cannot be combined with reference images, audio IDs, video clips, or character IDs. `last_frame_image_url` requires `first_frame_image_url`.

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.

`createAudio` is a synchronous resource and only exposes `run()`.
`createCharacter->run()` returns an immediate result when available. When RunAPI
accepts the request for background execution, it follows the Task Result URL
until the character response is ready.

RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToVideo`, `createAudio`, `createCharacter`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/gemini-omni
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/gemini-omni/text-to-video
- Flash 1.1 pricing and rate limits: https://runapi.ai/models/gemini-omni/flash-1-1
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/gemini-omni-php
- Multi-language SDK repository: https://github.com/runapi-ai/gemini-omni-sdk

## License

Licensed under the Apache License, Version 2.0.
