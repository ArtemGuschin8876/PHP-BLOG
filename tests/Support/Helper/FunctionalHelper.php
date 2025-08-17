<?php

namespace App\Tests\Support\Helper;

use App\User\Entity\User;
use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\Module\REST;
use Codeception\Module\Symfony;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FunctionalHelper extends Module
{
    /**
     * @throws ModuleException
     * @throws \Exception
     */
    public function loginAsUser(string $email, string $password): void
    {
        /** @var REST $rest */
        $rest = $this->getModule('REST');

        $rest->haveHttpHeader('Content-Type', 'application/json');
        $rest->sendPOST('/api/login_check', [
            'email' => $email,
            'password' => $password,
        ]);

        $rest->seeResponseCodeIs(Response::HTTP_OK);

        $token = $rest->grabDataFromResponseByJsonPath('$.token')[0];
        $rest->haveHttpHeader('Authorization', "Bearer {$token}");
    }

    /**
     * @param array{
     *   name: non-empty-string,
     *   email: non-empty-string,
     *   password: non-empty-string,
     *   roles?: list<non-empty-string>
     * } $data
     *
     * @throws ModuleException
     */
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
            roles: $data['roles'] ?? ['ROLE_USER'],
        );

        $encodedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $em->persist($user);
        $em->flush();

        return $user;
    }
}
