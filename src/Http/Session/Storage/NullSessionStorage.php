<?php

namespace App\Http\Session\Storage;

use App\Http\Session\SessionBagInterface;

class NullSessionStorage implements SessionStorageInterface
{
    /**
     * @var SessionBagInterface[]
     */
    private array $session = [];

    private bool $started = false;

    public function start(): bool
    {
        $this->session = [];
        $this->started = true;

        return true;
    }

    public function isStarted(): bool
    {
        return $this->started;
    }

    public function getId(): string
    {
        // TODO: Implement getId() method.
    }

    public function setId(string $id)
    {
        // TODO: Implement setId() method.
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    public function setName(string $name)
    {
        // TODO: Implement setName() method.
    }

    public function save(): bool
    {
        // TODO: Implement save() method.
    }

    public function clear(): bool
    {
        // TODO: Implement clear() method.
    }

    public function getBag(string $name): SessionBagInterface
    {
        // TODO: Implement getBag() method.
    }

    public function registerBag(SessionBagInterface $bag)
    {
        // TODO: Implement registerBag() method.
    }
}
