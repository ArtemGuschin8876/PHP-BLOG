<?php

namespace App\User\Response;

class UserUpdateResponse
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }
}
