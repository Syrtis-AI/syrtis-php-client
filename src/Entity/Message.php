<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Message extends \SyrtisClient\Entity\AbstractApiEntity
{
    final public const string ORIGIN_CARD = 'card';
    final public const string ORIGIN_CORE = 'core';
    final public const string ORIGIN_NODE = 'node';
    final public const string ORIGIN_USER = 'user';

    final public const string CONTENT_TYPE_ACTIONS = 'core/actions';
    final public const string CONTENT_TYPE_ANALYSIS = 'core/analysis';
    final public const string CONTENT_TYPE_CONVERSATION = 'core/conversation';
    final public const string CONTENT_TYPE_DEBUG = 'core/debug';
    final public const string CONTENT_TYPE_DOWNLOAD = 'core/download';
    final public const string CONTENT_TYPE_ERROR = 'core/error';
    final public const string CONTENT_TYPE_FORM = 'core/form';
    final public const string CONTENT_TYPE_HTTP_RESPONSE = 'core/http_response';
    final public const string CONTENT_TYPE_IMAGE = 'core/image';
    final public const string CONTENT_TYPE_INFO = 'web/info';
    final public const string CONTENT_TYPE_LIST = 'core/list';
    final public const string CONTENT_TYPE_NAVIGATE = 'core/navigate';
    final public const string CONTENT_TYPE_NAVIGATED = 'core/navigated';
    final public const string CONTENT_TYPE_PROGRESS = 'core/progress';
    final public const string CONTENT_TYPE_REFERENCE = 'core/reference';
    final public const string CONTENT_TYPE_SESSION_EXPIRE = 'core/session_expire';
    final public const string CONTENT_TYPE_SESSION_STATUS = 'core/session_status';
    final public const string CONTENT_TYPE_SUCCESS = 'web/success';
    final public const string CONTENT_TYPE_THOUGHT = 'core/thought';
    final public const string CONTENT_TYPE_UPLOAD = 'core/upload';
    final public const string CONTENT_TYPE_URL = 'core/url';
    final public const string CONTENT_TYPE_VIDEO = 'core/video';
    final public const string CONTENT_TYPE_WARNING = 'core/warning';
    // The types of a message's shape alone: a text, a value as JSON.
    final public const string CONTENT_TYPE_TEXT = 'core/text';
    final public const string CONTENT_TYPE_MARKDOWN = 'core/markdown';
    final public const string CONTENT_TYPE_HTML = 'core/html';
    final public const string CONTENT_TYPE_YAML = 'core/yaml';
    final public const string CONTENT_TYPE_JSON = 'core/json';


    public function getContent(): ?string
    {
        $value = $this->retrieveValue('content');

        return is_string($value) ? $value : null;
    }

    public function getContentType(): ?string
    {
        $value = $this->retrieveValue('contentType');

        return is_string($value) ? $value : null;
    }

    public function getName(): ?string
    {
        $value = $this->retrieveValue('name');

        return is_string($value) ? $value : null;
    }

    public function getOrigin(): ?string
    {
        $value = $this->retrieveValue('origin');

        return is_string($value) ? $value : null;
    }

    /**
     * A "reply" is a conversational message produced by the scenario,
     * as opposed to user input or technical messages.
     */
    public function isReply(): bool
    {
        return $this->getOrigin() === self::ORIGIN_NODE
            && $this->getContentType() === self::CONTENT_TYPE_CONVERSATION;
    }

    /**
     * A conversational message from any origin (user input included) —
     * what a chat history typically displays.
     */
    public function isConversation(): bool
    {
        return $this->getContentType() === self::CONTENT_TYPE_CONVERSATION;
    }

    /**
     * The content as its type reads it: what the api read of it
     * (`formattedContent`) when it came with the message; the JSON it encodes
     * when it decodes, the text otherwise.
     */
    public function getParsedContent(): mixed
    {
        $formatted = $this->retrieveValue('formattedContent') ?? $this->retrieveMetadata('formattedContent');
        if ($formatted !== null) {
            return $formatted;
        }

        $content = $this->getContent();
        if (! is_string($content)) {
            return $content;
        }

        try {
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return $content;
        }
    }
}
