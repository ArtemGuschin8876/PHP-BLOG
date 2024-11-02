<?php

declare(strict_types=1);

namespace App\Post\Service;

use App\Post\Entity\Post;
use App\Post\Repository\PostRepository;
use App\Post\Request\CreatePostDTO;
use App\Post\Request\UpdatePostDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return Post[]
     */
    public function getAllPosts(): array
    {
        return $this->postRepository->findAllPosts();
    }

    public function createPost(CreatePostDTO $createPostDTO): CreatePostDTO
    {

        $author = $this->postRepository->find($createPostDTO->getAuthorId());

        if (!$author) {
            throw new Exception('Author not found');
        }

        $post = new Post(
            $author,
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
        if (!$post instanceof Post) {
            throw new Exception('Post not found');
        }

        $post->setTitle($updatePostDTO->getTitle())
            ->setContent($updatePostDTO->getContent());

        $this->postRepository->save($post);

        return $updatePostDTO;
    }

    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findPostById($id);
    }
}
