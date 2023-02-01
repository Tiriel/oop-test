<?php

namespace App\Http\Session;

class MessageBag implements MessageBagInterface
{
    private string $storageKey;
    private array $messages = [];
    private string $name = '';

    public function __construct(string $storageKey = '_blog_messages')
    {
        $this->storageKey = $storageKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function add(string $key, string $message): void
    {
        $this->messages[$key][] = $message;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, array $default = []): array
    {
        if (!$this->has($key)) {
            return $default;
        }

        $messages = $this->messages[$key];
        unset($this->messages[$key]);

        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function peek(string $key, array $default = []): array
    {
        return $this->messages[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->empty();
    }

    /**
     * @inheritDoc
     */
    public function peekAll(): array
    {
        return $this->messages;
    }

    public function has(string $key)
    {
        return array_key_exists($key, $this->messages) && $this->messages[$key];
    }

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    public function init(array &$data): void
    {
        $this->messages = &$data;
    }

    public function empty(): array
    {
        $messages = $this->peekAll();
        $this->messages = [];

        return $messages;
    }
}
