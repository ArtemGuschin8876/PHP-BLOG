<?php

declare(strict_types=1);

namespace App\Post\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Post\Validator as PostAssert;
class CreatePostDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        private int $authorID,
        #[Assert\NotBlank]
        #[PostAssert\UniqueTitle]
        private string $title,
        #[Assert\NotBlank]
        private string $content,
    ) {
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'author' => $this->authorID,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthorID(): int
    {
        return $this->authorID;
    }
}
