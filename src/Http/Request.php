<?php

namespace App\Http;

class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHODS = [
        self::METHOD_GET,
        self::METHOD_OPTIONS,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_PATCH,
        self::METHOD_DELETE,
    ];
    private string $path = '';
    private string $method;
    private bool $isHttps;
    private array $attributes = [];
    private array $headers = [];
    private function __construct(
        public array $get = [],
        public array $post = [],
        public array $server = []
    ) {
        $this->server = array_change_key_case($this->server);
        $this->path = $this->server['request_uri'];
        $this->method = $this->server['request_method'] ?? '';
        $this->isHttps = (bool) ($this->server['https'] ?? false);

        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'http_')) {
                $this->headers[substr($key, 5)] = $value;
            } elseif (\in_array($key, ['content_type', 'content_length', 'content_md5'])) {
                $this->headers[$key] = $value;
            }
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function isHttps(): bool
    {
        return $this->isHttps;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function get(): array
    {
        return $this->get;
    }

    public function post(): array
    {
        return $this->post;
    }

    public function server(): array
    {
        return $this->server;
    }

    public static function create(): static
    {
        return new static($_GET, $_POST, $_SERVER);
    }
}
