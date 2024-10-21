<?php

namespace App\Service;

use App\DTO\CreatePostDTO;
use App\DTO\UpdatePostDTO;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
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


    public function createPost(CreatePostDTO $createPostDTO): array
    {
        $errors = $this->validator->validate($createPostDTO);
        if (count($errors) > 0) {
            $errorMessages[] = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage(),
                    'property' => $error->getPropertyPath(),
                ];
            }
            return ['status' => 'error', 'errors' => $errorMessages];
        }

        $post = new Post();
        $post->setTitle($createPostDTO->getTitle());
        $post->setContent($createPostDTO->getContent());

        $this->postRepository->save($post);

        return ['status' => 'success', 'post' => $createPostDTO->toArray()];
    }

    public function deletePost(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    public function updatePostByID(int $id, UpdatePostDTO $updatePostDTO): array
    {
        $post = $this->postRepository->findPostById($id);
        if (!$post) {
            return ['status' => 'error', 'message' => 'Post not found'];
        }

        $errors = $this->validator->validate($updatePostDTO);
        if (count($errors) > 0) {
            $errorMessages[] = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage(),
                    'property' => $error->getPropertyPath(),
                ];
            }
            return ['status' => 'error', 'errors' => $errorMessages];
        }

        if ($updatePostDTO->getTitle()) {
            $post->setTitle($updatePostDTO->getTitle());
        }
        if ($updatePostDTO->getContent()) {
            $post->setContent($updatePostDTO->getContent());
        }

        $this->postRepository->update($post);
        return ['status' => 'success', 'post' => $updatePostDTO->toArray()];
    }


    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findPostById($id);
    }

}