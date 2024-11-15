<?php

namespace App\User\Response;

class UserCreateResponse
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}
