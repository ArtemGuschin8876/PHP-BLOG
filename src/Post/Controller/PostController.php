<?php

declare(strict_types=1);

namespace App\Post\Controller;

use App\Post\Entity\Post;
use App\Post\Request\CreatePostDTO;
use App\Post\Request\UpdatePostDTO;
use App\Post\Response\PostCreateResponse;
use App\Post\Response\PostDetailResponse;
use App\Post\Response\PostGetResponse;
use App\Post\Response\PostUpdateResponse;
use App\Post\Service\PostService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api')]
class PostController extends AbstractController
{
    public function __construct(
        private PostService $postService,
    ) {
    }

    #[Route('/posts', name: 'posts', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all posts',
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: PostDetailResponse::class)),
                ),
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Bad request',
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
            ),
        ],
    )]
    public function list(): JsonResponse
    {
        $posts = $this->postService->getAllPosts();

        $data = $this->postService->getPostDetailResponses($posts);

        return $this->json($data);
    }

    #[Route('/post/{id}', name: 'post_show', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a single post',
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: new Model(type: PostGetResponse::class),
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Post not found',
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
            ),
        ],
    )]
    public function showPost(Post $post): JsonResponse
    {
        $data = $this->postService->getPostDetailResponse($post);

        return $this->json([$data], Response::HTTP_OK);
    }

    #[Route('/create', name: 'post_create', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a post',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: CreatePostDTO::class)),
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: new Model(type: PostCreateResponse::class),
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Validation errors',
            ),
        ]
    )]
    public function createPost(
        #[MapRequestPayload] CreatePostDTO $createPostDTO,
    ): JsonResponse {

        $result = $this->postService->createPost($createPostDTO);

        return $this->json(['data' => $result->toArray()], Response::HTTP_CREATED);

    }

    #[Route('/post/update/{id}', name: 'post_update', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update a post',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: UpdatePostDTO::class)),
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: new Model(type: PostUpdateResponse::class),
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Validation errors',
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found',
            ),
        ],
    )]
    public function updatePostByID(
        #[MapRequestPayload] UpdatePostDTO $updatePostDTO,
        int $id,
    ): JsonResponse {

        $result = $this->postService->updatePostByID($id, $updatePostDTO);

        return $this->json(['data' => $result->toArray()], Response::HTTP_OK);
    }

    #[Route('/post/delete/{id}', name: 'post_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete a post',
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: Response::HTTP_NO_CONTENT,
                description: 'User deleted successfully',
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Authentication required',
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Post not found',
            ),
        ],
    )]
    public function deletePostByID(Post $post): JsonResponse
    {
        $this->postService->deletePost($post);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
