# Changelog

## [v0.3.0](https://github.com/runapi-ai/gemini-omni-php/releases/tag/v0.3.0) - 2026-07-28

### Breaking
- Remove AudioTaskResponse and CompletedAudioTaskResponse; createAudio run() returns CreateAudioResponse directly.
  Migration: Replace references to the removed audio task response types with CreateAudioResponse and read its typed audio result.

### Added
- Decode typed Task Billing Facts on synchronous audio and character responses.


## [v0.2.0](https://github.com/runapi-ai/gemini-omni-php/releases/tag/v0.2.0) - 2026-07-20

### Added
- Add prompt-only Gemini Omni Flash Preview text-to-video requests with model-specific validation.


## [v0.1.0](https://github.com/runapi-ai/gemini-omni-php/releases/tag/v0.1.0) - 2026-06-25

### Added
- Publish the first RunAPI PHP Composer package release for `runapi-ai/gemini-omni`.
- Include typed PHP client resources, package README, Apache-2.0 license, and Composer CI.
