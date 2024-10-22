<?php

declare(strict_types=1);
namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPosts(): array
    {
        return $this->findAll();
    }

    public function save(Post $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function findPostById(int $id): ?Post
    {
        return $this->find($id);
    }

    public function delete(Post $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

}



