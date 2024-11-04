<?php

namespace App\User\Response;

class UserDetailResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {
    }
}
