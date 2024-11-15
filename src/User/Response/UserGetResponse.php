<?php

declare(strict_types=1);

namespace App\User\Response;

class UserGetResponse
{
    public function __construct(
        public int $id,
        public string $username,
        public string $email,
    ) {
    }
}
