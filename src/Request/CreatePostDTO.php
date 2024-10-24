<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePostDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $title;
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $content;

    /**
     * @param array<string,string> $data
     */
    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->content = $data['content'];
    }
    /**
     * @return string[]
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
