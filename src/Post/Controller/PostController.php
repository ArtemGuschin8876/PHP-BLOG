<?php

declare(strict_types=1);

namespace App\Post\Controller;

use App\Post\Entity\Post;
use App\Post\Normalizers\PostNormalizer;
use App\Post\Request\CreatePostDTO;
use App\Post\Request\UpdatePostDTO;
use App\Post\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $postService,
        private PostNormalizer $normalizer,
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
    public function createPost(
        #[MapRequestPayload] CreatePostDTO $createPostDTO,
    ): JsonResponse {

        $result = $this->postService->createPost($createPostDTO);

        return $this->json(['data' => $result->toArray()], Response::HTTP_CREATED);

    }

    #[Route('/post/update/{id}', name: 'post_update', methods: ['PUT'])]
    public function updatePostByID(
        #[MapRequestPayload] UpdatePostDTO $updatePostDTO,
        int $id,
    ): JsonResponse {

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
