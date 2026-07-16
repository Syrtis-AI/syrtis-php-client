<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Message;
use SyrtisClient\Entity\Session;
use SyrtisClient\Response\SessionHistory;
use SyrtisClient\Response\SessionSubscribeInfo;
use Wexample\PhpApi\Common\AbstractApiRepository;
use Wexample\PhpApi\Const\HttpMethod;
use Wexample\PhpApi\Exceptions\ApiEnvelopeException;

class SessionRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Session::class;
    }

    /**
     * Creates a new session on the given scenario and returns it hydrated
     * (its secureId is then used for sendMessage/fetchHistory).
     * Input validation is the API's job — payload is sent as-is.
     */
    public function createSession(
        string $scenarioSecureId,
        ?string $title = null,
    ): Session {
        $payload = ['scenarioSecureId' => $scenarioSecureId];
        if ($title !== null) {
            $payload['title'] = $title;
        }

        $response = $this->post('create', $payload);

        /** @var Session $session */
        $session = $this->hydrateFromApiItem($this->extractPayload($response));

        return $session;
    }

    /**
     * Sends one or several messages in a single POST — they all belong to
     * the same scenario request (one conversation turn). $content is a
     * shortcut for a first conversation/text message; $messages carries
     * the others (or all of them), each as an array accepting content,
     * name, contentType, format, stamps and metadata.
     *
     * When $sync is true, the API holds the HTTP response until the whole
     * scenario processing is done, so the returned messages include the
     * full reply.
     *
     * @param array<int, array<string, mixed>> $messages
     * @param array<int, \SplFileInfo|resource|string|array{contents: mixed, filename?: string}> $files
     * @param array<string, string[]> $fileStamps Keyed by file name.
     *
     * @return Message[]
     */
    public function sendMessage(
        string $sessionSecureId,
        ?string $content = null,
        array $messages = [],
        array $files = [],
        array $fileStamps = [],
        ?string $parentRequestSecureId = null,
        ?string $timeZone = null,
        bool $sync = false,
    ): array {
        $messagePayloads = [];

        $trimmedContent = is_string($content) ? trim($content) : '';
        if ($trimmedContent !== '') {
            $messagePayloads[] = $this->buildMessagePayload(['content' => $trimmedContent]);
        }

        foreach ($messages as $message) {
            $messagePayloads[] = $this->buildMessagePayload($message);
        }

        $response = $this->client->requestFormDataFromJson(
            $this->buildPath('continue/' . rawurlencode($sessionSecureId) . ($sync ? '/sync' : '')),
            [
                'messages' => $messagePayloads,
                'fileStamps' => $fileStamps === [] ? new \stdClass() : $fileStamps,
                'parentRequestSecureId' => $parentRequestSecureId,
                'timeZone' => $timeZone ?? date_default_timezone_get(),
            ],
            $files
        );

        return $this->hydrateMessages($response);
    }

    public function fetchHistory(
        string $sessionSecureId,
        int $page = 0,
        ?int $length = 50,
        ?string $lastRequestSecureId = null,
        ?string $orderBy = null,
        ?string $orderDirection = null,
    ): SessionHistory {
        $query = ['page' => $page];

        if ($length !== null) {
            $query['length'] = $length;
        }

        if ($lastRequestSecureId !== null) {
            $query['lastRequestSecureId'] = $lastRequestSecureId;
        }

        if ($orderBy !== null) {
            $query['orderBy'] = $orderBy;
        }

        if ($orderDirection !== null) {
            $query['orderDirection'] = $orderDirection;
        }

        $response = $this->client->requestJson(
            HttpMethod::GET,
            $this->buildPath('history/' . rawurlencode($sessionSecureId)),
            ['query' => $query]
        );

        $payload = $this->extractPayload($response);

        $hasMore = $payload['pagination']['hasMore'] ?? null;

        return new SessionHistory(
            messages: $this->getMessageRepository()->hydrateFromApiCollection(
                $this->extractItems($payload)
            ),
            hasMore: is_bool($hasMore) ? $hasMore : null,
        );
    }

    /**
     * Fetches a scoped, short-lived Mercure subscriber JWT for this session,
     * typically served to a frontend consuming live updates.
     */
    public function fetchSubscribeInfo(string $sessionSecureId): SessionSubscribeInfo
    {
        $response = $this->client->requestJson(
            HttpMethod::GET,
            $this->buildPath('subscribe-info/' . rawurlencode($sessionSecureId))
        );

        $payload = $this->extractPayload($response);

        $hubUrl = $payload['hubUrl'] ?? null;
        $jwt = $payload['jwt'] ?? null;

        if (! is_string($hubUrl) || ! is_string($jwt)) {
            throw new ApiEnvelopeException(
                message: 'Invalid subscribe-info payload: missing "hubUrl" or "jwt".',
                envelope: $response,
            );
        }

        $expiresAt = $payload['expiresAt'] ?? null;

        return new SessionSubscribeInfo(
            hubUrl: $hubUrl,
            jwt: $jwt,
            topics: is_array($payload['topics'] ?? null) ? $payload['topics'] : [],
            expiresAt: is_string($expiresAt) ? $expiresAt : null,
        );
    }

    /**
     * @param array<string, mixed> $message
     * @return array<string, mixed>
     */
    protected function buildMessagePayload(array $message): array
    {
        return [
            'content' => $message['content'] ?? null,
            'contentType' => $message['contentType'] ?? Message::CONTENT_TYPE_CONVERSATION,
            'format' => $message['format'] ?? Message::FORMAT_TEXT,
            'stamps' => $message['stamps'] ?? [],
            'metadata' => $message['metadata'] ?? null,
            'name' => $message['name'] ?? null,
        ];
    }

    /**
     * @return Message[]
     */
    protected function hydrateMessages(array $response): array
    {
        $payload = $this->extractPayload($response);

        if (! is_array($payload['messages'] ?? null)) {
            throw new ApiEnvelopeException(
                message: 'Invalid API payload: missing "messages" array.',
                envelope: $response,
            );
        }

        return $this->getMessageRepository()->hydrateFromApiCollection($payload['messages']);
    }

    protected function getMessageRepository(): MessageRepository
    {
        /** @var MessageRepository $repository */
        $repository = $this->client->getRepository(Message::class);

        return $repository;
    }
}
