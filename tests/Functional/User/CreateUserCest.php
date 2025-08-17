<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;
use Codeception\Util\HttpCode;

class CreateUserCest
{
    public function _before(FunctionalTester $I): void
    {
    }

    /**
     * @throws \Exception
     */
    public function createUserSuccessfully(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('api/users/', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'createdAt' => 'string:date',
        ], '$.data');

        $I->seeInRepository(
            User::class,
            [
                'id' => $I->grabDataFromResponseByJsonPath('data.id')[0],
                'createdAt' => new \DateTimeImmutable($I->grabDataFromResponseByJsonPath('data.createdAt')[0]),
            ]
        );
    }

    public function createUserValidationFailed(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('api/users/', [
            'name' => 'Test User',
            // отсутствует поле email и password
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'errors' => [
                [
                    'code' => 'integer',
                    'field' => 'string',
                    'message' => 'string',
                ],
            ],
        ], '$');

        $expectedErrors = [
            [
                'code' => 422,
                'field' => 'email',
                'message' => 'This value should be of type string.',
            ],
            [
                'code' => 422,
                'field' => 'password',
                'message' => 'This value should be of type string.',
            ],
        ];

        $I->seeResponseContainsJson(['errors' => $expectedErrors]);
    }
}
