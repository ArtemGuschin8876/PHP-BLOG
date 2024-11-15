<?php

namespace App\User\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserDetailResponse',
    type: 'object'
)]
class UserDetailResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {
    }
}
