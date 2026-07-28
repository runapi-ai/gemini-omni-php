# Gemini Omni PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/gemini-omni)](https://packagist.org/packages/runapi-ai/gemini-omni)
[![License](https://img.shields.io/github/license/runapi-ai/gemini-omni-php)](https://github.com/runapi-ai/gemini-omni-php/blob/main/LICENSE)

The Gemini Omni PHP SDK is the language-specific package for Gemini Omni
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `gemini-omni-php` split
repository. For model details, use https://runapi.ai/models/gemini-omni; for API
reference, use https://runapi.ai/docs#gemini-omni; for SDK docs, use
https://runapi.ai/docs#sdk-gemini-omni.

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

$task = $client->textToVideo->create([
    'model' => 'gemini-omni-flash-preview',
    'aspect_ratio' => '16:9',
    'audio_ids' => ['audio_1'],
    'character_ids' => ['character_1'],
    'duration_seconds' => 4,
    'output_resolution' => '720p',
    'prompt' => 'A precise product render on white marble',
    'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
    'seed' => 1,
    'video_list' => [['url' => 'https://cdn.runapi.ai/public/samples/video.mp4', 'start' => 0, 'ends' => 4]],
]);

$status = $client->textToVideo->get($task->id);

$result = $client->textToVideo->run([
    'model' => 'gemini-omni-flash-preview',
    'aspect_ratio' => '16:9',
    'audio_ids' => ['audio_1'],
    'character_ids' => ['character_1'],
    'duration_seconds' => 4,
    'output_resolution' => '720p',
    'prompt' => 'A serene mountain lake at dawn',
    'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
    'seed' => 1,
    'video_list' => [['url' => 'https://cdn.runapi.ai/public/samples/video.mp4', 'start' => 0, 'ends' => 4]],
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.

`createAudio` and `createCharacter` are synchronous resources and only expose `run()`.

RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToVideo`, `createAudio`, `createCharacter`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/gemini-omni
- SDK docs: https://runapi.ai/docs#sdk-gemini-omni
- Product docs: https://runapi.ai/docs#gemini-omni
- Pricing and rate limits: https://runapi.ai/models/gemini-omni
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/gemini-omni-php
- Multi-language SDK repository: https://github.com/runapi-ai/gemini-omni-sdk

## License

Licensed under the Apache License, Version 2.0.
