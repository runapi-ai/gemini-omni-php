# Changelog

## [v0.4.0](https://github.com/runapi-ai/gemini-omni-php/releases/tag/v0.4.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.3.2](https://github.com/runapi-ai/gemini-omni-php/releases/tag/v0.3.2) - 2026-09-28

### Added
- Return usage.cost as a float USD amount on completed async Task query and webhook envelopes.

### Removed
- Remove the public Task billing object from Task envelopes.
  Migration: Read usage.cost on completed Task envelopes. Create, processing, and failed envelopes omit usage.


## [v0.3.1](https://github.com/runapi-ai/gemini-omni-php/releases/tag/v0.3.1) - 2026-09-07

### Added
- Add the gemini-omni-flash-1-1 model with 360p through 4K video generation.
- Add first-frame and last-frame controls with model-specific request validation.
- Add an optional full-body reference image when creating a character and preserve both ordered character images.

### Changed
- Document that dual-image characters consume two video reference units.

### Fixed
- Follow accepted character Tasks through Task Result before returning the created character.


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
