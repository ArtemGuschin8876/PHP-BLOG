<?php

declare(strict_types=1);

namespace App\Service;

use App\Request\CreatePostDTO;
use App\Request\UpdatePostDTO;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use DateTimeImmutable;



class PostService
{

    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function getAllPosts(): array
    {
        return $this->postRepository->findAllPosts();
    }


    public function createPost(CreatePostDTO $createPostDTO): CreatePostDTO
    {
        $post = new Post(
            $createPostDTO->getTitle(),
            $createPostDTO->getContent(),
        );

        $this->postRepository->save($post);
        return $createPostDTO;
    }

    public function deletePost(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    public function updatePostByID(int $id, UpdatePostDTO $updatePostDTO): UpdatePostDTO
    {
        $post = $this->postRepository->findPostById($id);
        if (!$post) {
            throw new Exception('Post not found');
        }

        $this->postRepository->save($post);

        return $updatePostDTO;
    }


    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findPostById($id);
    }

}