<?php

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class ShowSingleUserCest
{
    private User $otherUser;

    public function _before(FunctionalTester $I): void
    {
        $this->otherUser = $I->createUser([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
    }

    public function showSingleUserSuccessfully(FunctionalTester $I): void
    {
        $I->sendGet('api/users/{id}'.$this->otherUser->getId());
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $this->otherUser->getId(),
            'name' => 'Test User',
            'email' => 'test@test.com',
        ]);
    }
}
