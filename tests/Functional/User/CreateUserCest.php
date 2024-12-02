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

        $response = json_decode($I->grabResponse(), true);

        $userID = $response['data']['id'];
        $userCreatedAt = $response['data']['createdAt'];

        $I->assertGreaterThan(0, $userCreatedAt);

        $I->assertNotFalse(
            \DateTime::createFromFormat(DATE_ATOM, $userCreatedAt),
            'The "createdAt" field is not a valid ISO 8601 datetime string.'
        );

        $I->seeInRepository(
            User::class,
            [
                'id' => $userID,
                'createdAt' => $userCreatedAt,
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

        $response = json_decode($I->grabResponse(), true);

        $I->assertArrayHasKey('errors', $response);

        $I->assertSame(
            $expectedErrors,
            $response['errors'],
            'The errors in the response do not match the expected errors.'
        );
    }
}
