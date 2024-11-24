<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

use App\User\Entity\User;
use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\Module\Symfony;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FunctionalHelper extends Module
{
    /**
     * @throws ModuleException
     */
    public function createUser(array $data): User
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');

        $container = $symfony->_getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.entity_manager');

        /** @var UserPasswordHasherInterface $passwordHashes */
        $passwordHashes = $container->get('security.user_password_hashes');

        $user = new User(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            roles: ['ROLE_USER'],
            createdAt: $data['createdAt'],
        );

        $encodedPassword = $passwordHashes->hashPassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $em->persist($user);
        $em->flush();

        return $user;
    }
}
