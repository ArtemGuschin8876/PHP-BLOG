<?php

namespace App\Tests\Support\Helper;

use App\User\Entity\User;
use Codeception\Module;
use Codeception\Module\Symfony;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FunctionalHelper extends Module
{
    public function createUser(array $data): User
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');

        $container = $symfony->_getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.entity_manager');

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = new User(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            roles: $data['ROLE_USER'],
            createdAt: $data['createdAt']
        );

        $encodedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $em->persist($user);
        $em->flush();

        return $user;
    }
}