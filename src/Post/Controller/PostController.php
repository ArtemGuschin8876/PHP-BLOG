<?php

declare(strict_types=1);

namespace App\Post\Controller;

use App\Post\Entity\Post;
use App\Post\Request\CreatePostRequestDTO;
use App\Post\Request\UpdatePostRequestDTO;
use App\Post\Response\PostCreateResponse;
use App\Post\Response\PostDetailResponse;
use App\Post\Response\PostGetResponse;
use App\Post\Response\PostUpdateResponse;
use App\Post\Service\PostService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/posts')]
class PostController extends AbstractController
{
    public function __construct(
        private readonly PostService $postService,
    ) {
    }

    #[Route('/', name: 'posts', methods: [Request::METHOD_GET])]
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
                response: Response::HTTP_NOT_FOUND,
                description: 'Bad request',
            ),
        ],
    )]
    public function list(): JsonResponse
    {
        $posts = $this->postService->getAllPosts();

        $result = $this->postService->getPostDetailResponses($posts);

        return $this->json(['data' => $result]);
    }

    #[Route('/{id}', name: 'post_show', methods: [Request::METHOD_GET])]
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
        ],
    )]
    public function showPost(Post $post): JsonResponse
    {
        $result = $this->postService->getPostDetailResponse($post);

        return $this->json(['data' => $result->toArray()], Response::HTTP_OK);
    }

    #[Route('/', name: 'post_create', methods: [Request::METHOD_POST])]
    #[OA\Post(
        summary: 'Create a post',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: CreatePostRequestDTO::class)),
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
                response: Response::HTTP_BAD_REQUEST,
                description: 'Validation errors',
            ),

        ]
    )]
    public function createPost(
        #[MapRequestPayload] CreatePostRequestDTO $createPostDTO,
    ): JsonResponse {

        $result = $this->postService->createPost(
            $createPostDTO->title,
            $createPostDTO->content,
            $createPostDTO->authorID
        );

        return $this->json(['data' => $result], Response::HTTP_CREATED);

    }

    #[Route('/{id}', name: 'post_update', methods: [Request::METHOD_PUT])]
    #[OA\Put(
        summary: 'Update a post',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: UpdatePostRequestDTO::class)),
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
        #[MapRequestPayload] UpdatePostRequestDTO $updatePostDTO,
        Post $post,
        #[CurrentUser] $currentUser,
    ): JsonResponse {

        $result = $this->postService->updatePost($post, $currentUser, $updatePostDTO);

        return $this->json(['data' => $result->toArray()], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'post_delete', methods: [Request::METHOD_DELETE])]
    #[OA\Delete(
        summary: 'Delete a post',
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: Response::HTTP_NO_CONTENT,
                description: 'User deleted successfully',
            ),

            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Post not found',
            ),
            new OA\Response(
                response: Response::HTTP_FORBIDDEN,
                description: 'Forbidden'
            ),
        ],
    )]
    public function deletePost(Post $post): JsonResponse
    {
        $this->postService->deletePost($post);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
