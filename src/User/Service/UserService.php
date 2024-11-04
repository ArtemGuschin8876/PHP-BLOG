<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Post\Entity\Post;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\Request\CreateUserDTO;
use App\User\Request\UpdateUserDTO;
use App\User\Response\UserDetailResponse;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->findAllUsers();
    }

    public function getUserByID(int $id): ?User
    {
        return $this->userRepository->findUserById($id);
    }

    public function createUser(CreateUserDTO $createUserDTO): CreateUserDTO
    {
        $user = new User(
            $createUserDTO->getName(),
            $createUserDTO->getEmail(),
        );

        $this->userRepository->save($user);

        return $createUserDTO;
    }

    public function updateUser(int $id, UpdateUserDTO $updateUserDTO): UpdateUserDTO
    {
        $user = $this->userRepository->findUserById($id);
        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }

        $user->setName($updateUserDTO->getName())
        ->setEmail($updateUserDTO->getEmail());

        $this->userRepository->save($user);

        return $updateUserDTO;
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->delete($user);
    }

    private function createMappedToDetailUser(User $user): UserDetailResponse
    {
        return new UserDetailResponse(
            $user->getId(),
            $user->getName(),
            $user->getEmail()
        );
    }

    public function getUserDetailResponse(User $user): UserDetailResponse
    {
        return $this->createMappedToDetailUser($user);
    }

    public function getUserDetailResponses(array $users): array
    {
        return array_map(fn (User $user) => $this->createMappedToDetailUser($user), $users);
    }
}
