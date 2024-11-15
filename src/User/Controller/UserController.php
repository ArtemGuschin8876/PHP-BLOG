<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\User\Entity\User;
use App\User\Request\CreateUserDTO;
use App\User\Request\UpdateUserDTO;
use App\User\Response\UserCreateResponse;
use App\User\Response\UserDetailResponse;
use App\User\Response\UserGetResponse;
use App\User\Response\UserUpdateResponse;
use App\User\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;

#[Route('/api')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all users',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: UserDetailResponse::class)),
                ),
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
            ),
        ],
    )]
    public function list(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        $data = $this->userService->getUserDetailResponses($users);

        return $this->json($data, Response::HTTP_OK);

    }

    #[Route('/user/{id}', name: 'user', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a single user',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: new Model(type: UserGetResponse::class)
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found',
            ),
        ],
    )]
    public function showUser(User $user): JsonResponse
    {
        $data = $this->userService->getUserDetailResponse($user);

        return  $this->json(['data' => $data], Response::HTTP_OK);
    }

    #[Route('/new', name: 'create_user', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create new user',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: CreateUserDTO::class)),
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: new Model(type: UserCreateResponse::class),
                        ),
                    ],
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Validation errors',
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized'
            ),
            new OA\Response(
                response: Response::HTTP_FORBIDDEN,
                description: 'Forbidden'
            ),
        ],
    )]
    public function createUser(
        #[MapRequestPayload] CreateUserDTO $createUserDTO,
    ): JsonResponse {

        $result = $this->userService->createUser($createUserDTO);

        return  $this->json(['data' => $result], Response::HTTP_CREATED);
    }

    #[Route('/user/update/{id}', name: 'update_user', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update user',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: UpdateUserDTO::class)),
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: new Model(type: UserUpdateResponse::class),
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
                response: Response::HTTP_FORBIDDEN,
                description: 'Forbidden'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found',
            ),
        ],
    )]
    public function updateUser(
        #[MapRequestPayload] UpdateUserDTO $updateUserDTO,
        int $id,
    ): JsonResponse {
        $result = $this->userService->updateUser($id, $updateUserDTO);

        return  $this->json(['data' => $result], Response::HTTP_OK);
    }

    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete a user',
        tags: ['Users'],
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
                response: Response::HTTP_FORBIDDEN,
                description: 'Forbidden'
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'User not found',
            ),
        ],
    )]
    public function deleteUser(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
