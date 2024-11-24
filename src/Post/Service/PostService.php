<?php

declare(strict_types=1);

namespace App\Post\Service;

use App\Post\Entity\Post;
use App\Post\Repository\PostRepository;
use App\Post\Request\UpdatePostRequestDTO;
use App\Post\Response\PostDetailResponse;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class PostService
{
    public function __construct(
        private UserRepository $userRepository,
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

    public function createPost(string $content, string $title, int $authorId): array
    {
        $author = $this->userRepository->find($authorId);

        if (!$author) {
            throw new Exception('Author not found');
        }

        $post = new Post(
            $author,
            $title,
            $content,
        );


        $this->postRepository->create($post);

        return [
            'id' => $post->getId(),
            'createdAt' => $post->getCreatedAt(),
        ];
    }

    public function deletePost(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    public function updatePost(Post $post, UpdatePostRequestDTO $updatePostDTO): Post
    {

        $post->setTitle($updatePostDTO->getTitle())
            ->setContent($updatePostDTO->getContent());

        $this->postRepository->save();

        return $post;
    }

    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findPostById($id);
    }

    private function createMappedToDetailPosts(Post $post): PostDetailResponse
    {
        return  new PostDetailResponse(
            id: $post->getId(),
            title: $post->getTitle(),
            content: $post->getContent(),
            date: $post->getCreatedAt()->format('Y-m-d H:i:s'),
            author: $post->getAuthor()->getId(),
        );
    }

    public function getPostDetailResponse(Post $post): PostDetailResponse
    {
        return $this->createMappedToDetailPosts($post);
    }

    public function getPostDetailResponses(array $posts): array
    {
        return array_map(fn (Post $post) => $this->createMappedToDetailPosts($post), $posts);
    }
}
