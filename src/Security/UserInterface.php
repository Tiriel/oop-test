<?php

namespace App\Security;

interface UserInterface
{
    public function getLogin(): string;

    public function getPassword(): string;

    public function getRoles(): array;
}
