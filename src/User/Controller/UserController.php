<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\User\Entity\User;
use App\User\Request\CreateUserDTO;
use App\User\Request\UpdateUserDTO;
use App\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        $data = $this->userService->getUserDetailResponses($users);

        return $this->json($data, Response::HTTP_OK);

    }

    #[Route('/user/{id}', name: 'user', methods: ['GET'])]
    public function showUser(User $user): JsonResponse
    {
        $data = $this->userService->getUserDetailResponse($user);

        return  $this->json(['data' => $data], Response::HTTP_OK);
    }

    #[Route('/new', name: 'create_user', methods: ['POST'])]
    public function createUser(
        #[MapRequestPayload] CreateUserDTO $createUserDTO,
    ): JsonResponse {

        $result = $this->userService->createUser($createUserDTO);

        return  $this->json(['data' => $result], Response::HTTP_CREATED);
    }

    #[Route('/user/update/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(
        #[MapRequestPayload] UpdateUserDTO $updateUserDTO,
        int $id,
    ): JsonResponse {
        $result = $this->userService->updateUser($id, $updateUserDTO);

        return  $this->json(['data' => $result], Response::HTTP_OK);
    }

    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);

        return $this->json(['status' => 'User deleted'], Response::HTTP_OK);

    }
}
