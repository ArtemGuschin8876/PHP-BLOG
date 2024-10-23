<?php

declare(strict_types=1);

namespace App\Service;

use App\Request\CreatePostDTO;
use App\Request\UpdatePostDTO;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PostService
{
    private PostRepository $postRepository;
    private ValidatorInterface $validator;


    public function __construct(PostRepository $postRepository, ValidatorInterface $validator)
    {
        $this->postRepository = $postRepository;
        $this->validator = $validator;
    }

    public function getAllPosts(): array
    {
        return $this->postRepository->findAllPosts();
    }


    public function createPost(CreatePostDTO $createPostDTO): CreatePostDTO
    {
        $errors = $this->validator->validate($createPostDTO);
        if (count($errors) > 0) {
            throw new ValidationFailedException($createPostDTO, $errors);
        }

        $post = new Post(
            $createPostDTO->getTitle(),
            $createPostDTO->getContent(),
            new \DateTimeImmutable()
        );

        $this->postRepository->save($post);
        return new CreatePostDTO([
            'title' => $post->getTitle(),
            'content' => $post->getContent()
        ]);
    }

    public function deletePost(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    public function updatePostByID(int $id, UpdatePostDTO $updatePostDTO): UpdatePostDTO
    {
        $post = $this->postRepository->findPostById($id);
        if (!$post) {
            throw new \Exception('Post not found');
        }

        $errors = $this->validator->validate($updatePostDTO);
        if (count($errors) > 0) {
            throw new ValidationFailedException($updatePostDTO, $errors);
        }

        if ($updatePostDTO->getTitle()) {
            $post->setTitle($updatePostDTO->getTitle());
        }
        if ($updatePostDTO->getContent()) {
            $post->setContent($updatePostDTO->getContent());
        }

        $this->postRepository->save($post);

        return new UpdatePostDTO([
            'title' => $updatePostDTO->getTitle(),
            'content' => $updatePostDTO->getContent()
        ]);
    }


    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findPostById($id);
    }

}