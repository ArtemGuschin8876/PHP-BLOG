<?php

declare(strict_types=1);

namespace App\Post\Request;

use App\Post\Validator as PostAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public int $authorID,
        #[Assert\NotBlank]
        #[PostAssert\UniqueTitle]
        public string $title,
        #[Assert\NotBlank]
        public string $content,
    ) {
    }
}
