<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;

class ShowAllUsersCest
{
    private User $userOne;
    private User $userTwo;

    public function _before(FunctionalTester $I): void
    {
        $this->userOne = $I->createUser([
            'name' => 'UserOne',
            'email' => 'userOne@user.com',
            'password' => 'PasswordUserOne',
        ]);

        $this->userTwo = $I->createUser([
            'name' => 'UserTwo',
            'email' => 'userTwo@user.com',
            'password' => 'PasswordUserTwo',
        ]);
    }

    public function showAllUsersSuccessfully(FunctionalTester $I): void
    {
        $I->sendGet('/api/users/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'id' => $this->userOne->getId(),
                'name' => $this->userOne->getName(),
                'email' => $this->userOne->getEmail(),
            ],
            [
                'id' => $this->userTwo->getId(),
                'name' => $this->userTwo->getName(),
                'email' => $this->userTwo->getEmail(),
            ],
        ]);
    }
}
