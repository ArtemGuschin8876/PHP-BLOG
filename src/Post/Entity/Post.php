<?php

declare(strict_types=1);

namespace App\Post\Entity;

use App\Post\Repository\PostRepository;
use App\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts', indexes: [new ORM\Index(name: 'title_index', columns: ['title'])])]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
        private readonly User $author,
        #[ORM\Column(length: 255)]
        private string $title,
        #[ORM\Column(type: 'text')]
        private string $content,
        #[ORM\Column(type: 'datetime_immutable')]
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
    }

    /**
     * @return array{
     *  title:string,
     *  content:string
     *  }
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }
}
