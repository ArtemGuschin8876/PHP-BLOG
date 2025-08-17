<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class ShowSingleUserCest
{
    private User $otherUser;

    public function _before(FunctionalTester $I): void
    {
        $I->createUser([
            'name' => 'otherUser',
            'email' => 'otherUser@other.com',
            'password' => 'other',
            'roles' => ['ROLE_USER'],
        ]);

        $this->otherUser = $I->createUser([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function showSingleUserSuccessfully(FunctionalTester $I): void
    {
        $I->loginAsUser('otherUser@other.com', 'other');
        $I->sendGet('api/users/'.$this->otherUser->getId());
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'data' => [
                'id' => $this->otherUser->getId(),
                'name' => $this->otherUser->getName(),
                'email' => $this->otherUser->getEmail(),
            ],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function showSingleUserNotFound(FunctionalTester $I): void
    {
        $I->loginAsUser('otherUser@other.com', 'other');

        $I->sendGet('api/users/0000');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }
}
