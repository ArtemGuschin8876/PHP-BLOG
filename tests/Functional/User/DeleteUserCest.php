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
        $this->targetUser = $I->createUser([
            'name' => 'User to delete',
            'email' => 'userToDelete@email.com',
            'password' => 'UserToDeletePassword',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function deleteUserSuccessfully(FunctionalTester $I): void
    {
        $I->loginAsUser('CAT@gmail.com', 'CAT');

        $I->sendDELETE('api/users/'.$this->targetUser->getId());

        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);

        $I->dontSeeInRepository(User::class, ['email' => 'userToDelete@email.com']);


    }
}
