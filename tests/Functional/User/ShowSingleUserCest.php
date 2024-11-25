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
        $this->otherUser = $I->createUser([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
    }

    public function showSingleUserSuccessfully(FunctionalTester $I): void
    {
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

    public function showSingleUserNotFound(FunctionalTester $I): void
    {
        $I->sendGet('api/users/0000');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    public function showSingleUserInvalidID(FunctionalTester $I): void
    {
        $I->sendGet('api/users/invalid-id');
        $I->seeResponseCodeIs(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
