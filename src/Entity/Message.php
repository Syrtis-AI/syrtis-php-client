<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Message extends \SyrtisClient\Entity\AbstractApiEntity
{
    final public const string ORIGIN_CARD = 'card';
    final public const string ORIGIN_CORE = 'core';
    final public const string ORIGIN_NODE = 'node';
    final public const string ORIGIN_USER = 'user';

    final public const string CONTENT_TYPE_ACTIONS = 'actions';
    final public const string CONTENT_TYPE_ANALYSIS = 'analysis';
    final public const string CONTENT_TYPE_CONVERSATION = 'conversation';
    final public const string CONTENT_TYPE_DEBUG = 'debug';
    final public const string CONTENT_TYPE_DEFAULT = 'default';
    final public const string CONTENT_TYPE_DOWNLOAD = 'download';
    final public const string CONTENT_TYPE_ERROR = 'error';
    final public const string CONTENT_TYPE_FORM = 'form';
    final public const string CONTENT_TYPE_HTTP_RESPONSE = 'http_response';
    final public const string CONTENT_TYPE_IMAGE = 'image';
    final public const string CONTENT_TYPE_INFO = 'info';
    final public const string CONTENT_TYPE_LIST = 'list';
    final public const string CONTENT_TYPE_NAVIGATE = 'navigate';
    final public const string CONTENT_TYPE_NAVIGATED = 'navigated';
    final public const string CONTENT_TYPE_PROGRESS = 'progress';
    final public const string CONTENT_TYPE_REFERENCE = 'reference';
    final public const string CONTENT_TYPE_SESSION = 'session';
    final public const string CONTENT_TYPE_SESSION_EXPIRE = 'session_expire';
    final public const string CONTENT_TYPE_SESSION_STATUS = 'session_status';
    final public const string CONTENT_TYPE_SUCCESS = 'success';
    final public const string CONTENT_TYPE_THOUGHT = 'thought';
    final public const string CONTENT_TYPE_UPLOAD = 'upload';
    final public const string CONTENT_TYPE_URL = 'url';
    final public const string CONTENT_TYPE_VIDEO = 'video';
    final public const string CONTENT_TYPE_WARNING = 'warning';

    final public const string FORMAT_BOOLEAN = 'boolean';
    final public const string FORMAT_FLOAT = 'float';
    final public const string FORMAT_HTML = 'html';
    final public const string FORMAT_INTEGER = 'integer';
    final public const string FORMAT_JSON = 'json';
    final public const string FORMAT_MARKDOWN = 'markdown';
    final public const string FORMAT_NULL = 'null';
    final public const string FORMAT_TEXT = 'text';
    final public const string FORMAT_YAML = 'yaml';

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

    public function getFormat(): ?string
    {
        $value = $this->retrieveValue('format');

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
     * For JSON messages, the server already parses the content into
     * metadata.formattedContent; falls back to decoding the raw content.
     */
    public function getParsedContent(): mixed
    {
        if ($this->getFormat() !== self::FORMAT_JSON) {
            return $this->getContent();
        }

        $formatted = $this->retrieveMetadata('formattedContent');
        if ($formatted !== null) {
            return $formatted;
        }

        $content = $this->getContent();
        if (is_string($content) && trim($content) !== '') {
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }
}
