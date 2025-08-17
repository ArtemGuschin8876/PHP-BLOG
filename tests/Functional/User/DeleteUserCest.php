<?php

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserCest
{
    private User $targetUser;

    public function _before(FunctionalTester $I): void
    {
        $I->createUser([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => 'AdminPassword',
            'roles' => ['ROLE_ADMINISTRATOR'],
        ]);

        $I->createUser([
            'name' => 'otherUser',
            'email' => 'otherUser@test.com',
            'password' => 'OtherPassword',
            'roles' => ['ROLE_USER'],
        ]);

        $this->targetUser = $I->createUser([
            'name' => 'userToDelete',
            'email' => 'userToDelete@email.com',
            'password' => 'UserToDeletePassword',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function deleteUserSuccessfullyByAdmin(FunctionalTester $I): void
    {
        $I->loginAsUser('admin@test.com', 'AdminPassword');

        $I->sendDELETE('api/users/'.$this->targetUser->getId());

        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);

        $I->dontSeeInRepository(User::class, [
            'id' => $this->targetUser->getId(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function deleteUserForbiddenForDifferentUser(FunctionalTester $I): void
    {
        $I->loginAsUser('otherUser@test.com', 'OtherPassword');

        $I->sendDELETE('api/users/'.$this->targetUser->getId());

        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);

        $I->seeInRepository(User::class, [
            'id' => $this->targetUser->getId(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function deleteUserNotFound(FunctionalTester $I): void
    {
        $I->loginAsUser('admin@test.com', 'AdminPassword');

        $I->sendDELETE('api/users/0000');

        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    public function deleteUserUnauthorized(FunctionalTester $I): void
    {
        $I->sendDELETE('api/users/'.$this->targetUser->getId());

        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);

        $I->seeInRepository(User::class, [
            'id' => $this->targetUser->getId(),
        ]);
    }
}
