<?php

declare(strict_types=1);

namespace App\Post\Request;

use App\Post\Validator as PostAssert;
#[PostAssert\ValidPost]
class CreatePostDTO
{
    public function __construct(
        public string $author,
        #[PostAssert\UniqueTitle]
        public string $title,
        public string $content,
    ) {
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'author' => $this->author,
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

    public function getAuthor(): string
    {
        return $this->author;
    }
}
