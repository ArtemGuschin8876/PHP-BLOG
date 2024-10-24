<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Normalizers\PostNormalizer;
use App\Request\CreatePostDTO;
use App\Request\UpdatePostDTO;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $postService,
        private PostNormalizer $normalizer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('/posts', name: 'posts', methods: ['GET'])]
    public function list(): Response
    {
        $posts = $this->postService->getAllPosts();

        $normalizedPosts = array_map(function ($post) {
            return $this->normalizer->normalize(
                $post,
                null,
                ['mode' => 'default']
            );
        }, $posts);

        return $this->json(['data' => $normalizedPosts], Response::HTTP_OK);
    }

    #[Route('/post/{id}', name: 'post_show', methods: ['GET'])]
    public function showPost(Post $post): JsonResponse
    {
        $normalizedPost = $this->normalizer->normalize(
            $post,
            null,
            ['mode' => 'default']
        );

        return $this->json(['data' => $normalizedPost], Response::HTTP_OK);
    }

    #[Route('/create', name: 'post_create', methods: ['POST'])]
    public function createPost(Request $request): JsonResponse
    {
        $createPostDTO = new CreatePostDTO($request->toArray());

        $errors = $this->validator->validate($createPostDTO);
        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage(),
                    'property' => $error->getPropertyPath(),
                ];
            }

            return $this->json(['validation_errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->postService->createPost($createPostDTO);

        return $this->json(['data' => $result->toArray()], Response::HTTP_CREATED);
    }

    #[Route('/post/update/{id}', name: 'post_update', methods: ['PUT'])]
    public function updatePostByID(Request $request, int $id): JsonResponse
    {
        $updatePostDTO = new UpdatePostDTO($request->toArray());

        $errors = $this->validator->validate($updatePostDTO);
        if (count($errors) > 0) {
            $errorsMessages = [];

            foreach ($errors as $error) {
                $errorsMessages[] = [
                    'message' => $error->getMessage(),
                    'property' => $error->getPropertyPath(),
                ];
            }

            return $this->json(['validation_errors' => $errorsMessages], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->postService->updatePostByID($id, $updatePostDTO);

        return $this->json(['data' => $result->toArray()], Response::HTTP_OK);
    }

    #[Route('/post/delete/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function deletePostByID(Post $post): JsonResponse
    {
        $this->postService->deletePost($post);

        return $this->json(['status' => 'Post deleted'], Response::HTTP_OK);
    }
}
