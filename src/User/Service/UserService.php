<?php

declare(strict_types=1);

namespace App\User\Service;

use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\Response\UserDetailResponse;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserService
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

    /**
     * @return array{
     *     id:int,
     *     createdAt:DateTimeImmutable
     * }
     */
    public function createUser(string $name, string $email, string $password): array
    {
        $user = new User(
            $name,
            $email,
            $password,
        );

        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->userRepository->create($user);

        return [
            'id' => $user->getId(),
            'createdAt' => $user->getCreatedAt(),
        ];
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

    /**
     * @param User[] $users
     *
     * @return UserDetailResponse[]
     */
    public function getUserDetailResponses(array $users): array
    {
        return array_map(fn (User $user) => $this->createMappedToDetailUser($user), $users);
    }
}
