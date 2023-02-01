<?php

namespace App\Http\Session;

use Traversable;

class SessionBag implements SessionBagInterface, \IteratorAggregate, \Countable
{
    private string $storageKey;
    private array $data = [];
    private string $name = '';

    public function __construct(string $storageKey = '_blog_session') {
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

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    public function init(array &$data): void
    {
        $this->data = &$data;
    }

    public function empty(): array
    {
        $data = $this->data;
        $this->data = [];

        return $data;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }
}
