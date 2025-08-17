<?php

declare(strict_types=1);

namespace App\User\Request;

use App\User\Validator as UserValidator;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Email]
        #[Assert\NotBlank]
        #[UserValidator\UniqueEmail]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}
