<?php

namespace App\Http\Session;

use App\Http\Session\Storage\NativeSessionStorage;
use App\Http\Session\Storage\SessionStorageInterface;
use Traversable;

class Session implements \IteratorAggregate, \Countable
{
    private SessionStorageInterface $storage;

    public function __construct(
        SessionStorageInterface $storage = null,
        SessionBagInterface $sessionBag = null,
        MessageBagInterface $messageBag = null
    ) {
        $this->storage = $storage ?? new NativeSessionStorage();
        $sessionBag ??= new SessionBag();
        $this->storage->registerBag($sessionBag);

        $messageBag ??= new MessageBag();
        $this->storage->registerBag($messageBag);
    }

    public function getIterator(): Traversable
    {
        // TODO: Implement getIterator() method.
    }

    public function count(): int
    {
        // TODO: Implement count() method.
    }
}
