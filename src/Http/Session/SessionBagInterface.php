<?php

namespace App\Http\Session;

interface SessionBagInterface
{
    public function getName(): string;

    public function setName(string $name): void;
    public function getStorageKey(): string;

    public function init(array &$data): void;

    public function empty(): array;
}
