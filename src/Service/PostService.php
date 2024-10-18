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

    public function deletePost(int $id):void
    {
        $post = $this->postRepository->findPostById($id);

        if (!$post) {
            throw new \Exception("Post not found");
        }

        $this->postRepository->delete($post, true);
    }

    public function updatePostByID(int $id, array $data):void
    {
        $post = $this->postRepository->findPostById($id);
        if (!$post) {
            throw new \Exception("Post not found");
        }

        if (isset($data['title'])) {
            $post->setTitle($data['title']);
        }
        if (isset($data['content'])) {
            $post->setContent($data['content']);
        }
        $this->postRepository->update($post);
    }


    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findPostById($id);
    }

}