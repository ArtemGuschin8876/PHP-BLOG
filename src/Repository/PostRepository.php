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

    public function findAllPosts():array
    {
       return  $this->findAll();
    }

    public function save(Post $post):void
    {   
        $entityManager = $this->getEntityManager();

        $entityManager->persist($post);
        $entityManager->flush();
    }
}
