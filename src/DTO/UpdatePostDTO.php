<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $title;
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $content;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->content = $data['content'];
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content
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