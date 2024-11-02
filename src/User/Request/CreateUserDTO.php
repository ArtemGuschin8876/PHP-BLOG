<?php

declare(strict_types=1);

namespace App\User\Request;

use App\User\Validator as UserValidator;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        private string $name,
        #[Assert\Email]
        #[Assert\NotBlank]
        #[UserValidator\UniqueEmail]
        private string $email,
    ) {
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
