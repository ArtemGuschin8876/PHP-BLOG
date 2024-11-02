<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Post\Entity\Post;
use App\User\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\Column(type: 'string', length: 180, unique: true)]
        private string $email,
        #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author')]
        private Collection $posts = new ArrayCollection(),
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;

    }
}
