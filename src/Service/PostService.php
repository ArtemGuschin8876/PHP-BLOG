<?php 

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;


class PostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts():array
    {
        return $this->postRepository->findAllPosts();
    }

    public function createPost($title, $content): Post
    {
       $post = new Post;
       $post->setTitle($title);
       $post->setContent($content);

       $this->postRepository->save($post);

       return $post;
    }
}