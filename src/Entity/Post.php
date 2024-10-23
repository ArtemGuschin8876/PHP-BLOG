<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'post')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function __construct(
        #[ORM\Column(length: 255)]
        private ?string             $title = null,
        #[ORM\Column(type: 'text')]
        private ?string             $content = null,
        #[ORM\Column(type: 'datetime_immutable')]
        private ?DateTimeImmutable $createdAt = new DateTimeImmutable(),
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

}
