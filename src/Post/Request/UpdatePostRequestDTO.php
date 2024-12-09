<?php

declare(strict_types=1);

namespace App\Post\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotBlank]
        public string $content,
    ) {
    }

    /**
     * @return array{
     *    title:string,
     *    content:string
     * }
     */
    public function toArray(): array
    {
        return [
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
}
