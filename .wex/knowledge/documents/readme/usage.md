## Installation

```bash
composer require syrtis/php-client
```

## Instantiation

```php
use SyrtisClient\Common\SyrtisClient;

$client = new SyrtisClient(
    host: 'https://api.syrtis.ai',
    apiKey: 'your_token_here',
);
```

By default the client targets the latest API version (`2026-1`). To pin a specific version, pass `apiVersion: SyrtisClient::API_VERSION_2025_3`.

## Repositories

```php
use SyrtisClient\Entity\Session;
use SyrtisClient\Repository\SessionRepository;

/** @var SessionRepository $sessionRepository */
$sessionRepository = $client->getRepository(Session::class);
```

## Creating a session

```php
$session = $sessionRepository->createSession(
    scenarioSecureId: 'sce_…',
    title: 'Support conversation', // optional
);
// $session->getSecureId() → use it for sendMessage / fetchHistory
```

## Sending a message

`sendMessage` posts to `session/continue/{secureId}` and returns hydrated `Message[]` entities (the messages produced by the scenario):

```php
$messages = $sessionRepository->sendMessage(
    sessionSecureId: $sessionSecureId,
    content: 'Hello',
);
```

### Synchronous mode

By default the API responds as soon as the request is accepted. With `sync: true`, the HTTP response is held until the whole scenario processing is done, so the returned messages include the full reply:

```php
$messages = $sessionRepository->sendMessage(
    sessionSecureId: $sessionSecureId,
    content: 'Hello',
    sync: true,
);
```

### Sending several messages in one request

All messages of a single `sendMessage` call belong to the same scenario request — one conversation turn. `content` is a shortcut for a first `conversation`/`text` message; `messages` carries the others (or all of them), each accepting `content`, `name`, `contentType`, `format`, `stamps` and `metadata`:

```php
$messages = $sessionRepository->sendMessage(
    sessionSecureId: $sessionSecureId,
    content: 'Hello',
    messages: [
        ['content' => 'Your name is Marion', 'name' => 'CONTEXT_INSTRUCTION'],
    ],
    sync: true,
);
```

### Uploading files

Files are sent in the same multipart POST (fields `upload_0`, `upload_1`, …). Each file may be a path, an `SplFileInfo`, an open resource, or a Guzzle multipart part:

```php
$messages = $sessionRepository->sendMessage(
    sessionSecureId: $sessionSecureId,
    content: 'Here are the documents',
    files: [new \SplFileInfo('/path/to/report.pdf')],
    fileStamps: ['report.pdf' => ['stamp_name']], // keyed by file name
);
```

## Working with messages

`Message` exposes explicit getters (`getContent()`, `getContentType()`, `getFormat()`, `getName()`, `getOrigin()`) plus the discriminant constants used by the API (`Message::ORIGIN_*`, `Message::CONTENT_TYPE_*`, `Message::FORMAT_*`).

```php
use SyrtisClient\Entity\Message;

// Conversational replies produced by the scenario (origin node + contentType conversation):
$replies = array_filter($messages, fn (Message $m) => $m->isReply());

// Chat history display: conversational messages from any origin.
$visible = array_filter($messages, fn (Message $m) => $m->isConversation());

// Structured content: for format 'json' messages, getParsedContent() returns the
// server-parsed value (metadata.formattedContent), falling back to json_decode(content).
// For other formats it returns the raw content.
$data = $extracted?->getParsedContent();
```

## Fetching history

```php
$history = $sessionRepository->fetchHistory(
    sessionSecureId: $sessionSecureId,
    page: 0,
    length: 50,
);

$history->getMessages(); // Message[]
$history->getHasMore();  // bool, or null when the API did not paginate
```

Also supports `lastRequestSecureId`, `orderBy` and `orderDirection`.

Conversations are trees (message versions create branches). `lastRequestSecureId` selects which branch the history walks: it anchors the walk on that request and returns messages from its branch (the request and its ancestors). Once anchored, pages are stable — messages sent after the anchor never shift the pagination. For paginated consumption, capture the request secureId of the first batch and keep passing it.

## Live updates (Mercure)

Server-side PHP does not hold SSE subscriptions — use `sync: true` to wait for a reply. To let a frontend subscribe, fetch a scoped, short-lived subscriber JWT and hand it over:

```php
$info = $sessionRepository->fetchSubscribeInfo($sessionSecureId);

$info->getHubUrl();    // Mercure hub public URL
$info->getJwt();       // subscriber JWT scoped to this session's topics
$info->getTopics();    // includes '{apiVersion}/entity/session/event/{secureId}'
$info->getExpiresAt(); // ISO8601 — fetch a fresh one before expiration
```

Frontends should listen to the versioned topic (`{apiVersion}/` prefix, see `$client->getApiVersion()`): its events carry `{event, data}` where `data` is the standard API item `{type, entity, metadata, relationships}`, identical to REST responses. The payload is strictly validated: all four fields are required, or an `ApiEnvelopeException` is thrown.

## Error handling

All API responses use the standard envelope `{type, code, message?, data}`. Unwrapping is centralized in `wexample/php-api`: when the server answers `type: 'error'`, repository calls throw an `ApiEnvelopeException` carrying the server error key.

```php
use Wexample\PhpApi\Exceptions\ApiEnvelopeException;

try {
    $sessionRepository->sendMessage(sessionSecureId: $id, content: 'Hello', sync: true);
} catch (ApiEnvelopeException $exception) {
    $exception->getMessage();      // server error key (e.g. 'ERR_INVALID_CREDENTIALS')
    $exception->getResponseCode(); // numeric code from the envelope
    $exception->getEnvelope();     // raw response for inspection
}
```

HTTP-level failures (4xx/5xx, transport) still raise `ApiException`.

Hydration is strict by design: a response field absent from the entity schema, a malformed API item, a type mismatch or an unregistered relationship type throws an `ApiSchemaException` (`Wexample\PhpApi\Exceptions\ApiSchemaException`, `getErrorCode()` returning an `ERR_SCHEMA_*` constant, plus `getEntityName()`/`getField()`). A contract drift is caught at the boundary instead of silently losing data.

## Design rules

- No client-side field validation: payloads are sent as-is, the API is the validator.
- Prefer explicit methods (`getParsedContent()`) over magic accessors when extending entities.
