<?php

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;

class ShowSingleUserCest
{
    private User $otherUser;

    public function before(FunctionalTester $I): void
    {
        $this->otherUser = $I->createUser([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'roles' => 'ROLE_USER',
            'createdAt' => new \DateTime(),
        ]);
    }

    public function showSingleUser(FunctionalTester $I): void
    {
        $I->sendGET('/users');


    }
}
