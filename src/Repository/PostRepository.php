<?php

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
        $entityManager = $this->getEntityManager();

        $entityManager->persist($post);
        $entityManager->flush();
    }

    public function findPostById(int $id): ?Post
    {
        return $this->find($id);
    }

    public function update(Post $post): void
    {
        $this->getEntityManager()->flush($post);
    }

    public function delete(Post $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

}



