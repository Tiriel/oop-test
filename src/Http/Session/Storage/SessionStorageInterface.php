<?php

namespace App\Http\Session\Storage;

use App\Http\Session\SessionBagInterface;

interface SessionStorageInterface
{
    public function start(): bool;

    public function isStarted(): bool;

    public function getId(): string;

    public function setId(string $id);

    public function getName(): string;

    public function setName(string $name);

    public function save(): bool;

    public function clear():bool;

    public function getBag(string $name): SessionBagInterface;
    public function registerBag(SessionBagInterface $bag);
}
