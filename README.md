# Gemini Omni PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/gemini-omni)](https://packagist.org/packages/runapi-ai/gemini-omni)
[![License](https://img.shields.io/github/license/runapi-ai/gemini-omni-php)](https://github.com/runapi-ai/gemini-omni-php/blob/main/LICENSE)

The Gemini Omni PHP SDK is the Composer package for Gemini Omni on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

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

$voice = $client->createAudio->run([
    'model' => 'gemini-omni-audio',
    'audio_id' => 'zephyr',
    'name' => 'Narrator',
]);

$task = $client->textToVideo->create([
    'model' => 'gemini-omni-flash-preview',
    'prompt' => 'A paper airplane glides through a sunlit studio.',
    'aspect_ratio' => '16:9',
    'output_resolution' => '720p',
]);

$status = $client->textToVideo->get($task->id);

$result = $client->textToVideo->run([
    'model' => 'gemini-omni-flash-preview',
    'prompt' => 'A tiny paper boat floats through a glowing cave.',
    'aspect_ratio' => '9:16',
    'output_resolution' => '720p',
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/gemini-omni
- SDK docs: https://runapi.ai/docs#sdk-gemini-omni
- Product docs: https://runapi.ai/docs#gemini-omni
- Flash Preview pricing and rate limits: https://runapi.ai/models/gemini-omni/flash-preview
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/gemini-omni-php
- Multi-language SDK repository: https://github.com/runapi-ai/gemini-omni-sdk

## License

Licensed under the Apache License, Version 2.0.
