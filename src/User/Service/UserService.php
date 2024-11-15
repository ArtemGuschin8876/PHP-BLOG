<?php

declare(strict_types=1);

namespace App\User\Service;

use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\Request\CreateUserDTO;
use App\User\Request\UpdateUserDTO;
use App\User\Response\UserDetailResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
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
            $createUserDTO->getPassword(),
        );

        $user->setRoles(['ROLE_USER', 'ROLE_USER_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $createUserDTO->getPassword());
        $user->setPassword($hashedPassword);

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
