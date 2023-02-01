<?php

namespace App\Http\Session;

interface MessageBagInterface extends SessionBagInterface
{
    public function add(string $key, string $message): void;

    /**
     * Gets and clears messages under the given key
     */
    public function get(string $key, array $default = []): array;

    /**
     * Gets without clearing messages under the given key
     */
    public function peek(string $key, array $default = []): array;

    /**
     * Gets and clears all messages
     */
    public function getAll(): array;

    /**
     * Gets without clearing all messages
     */
    public function peekAll(): array;

    public function has(string $key);
}
