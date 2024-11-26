<?php

namespace App\Tests\Functional\User;

use App\Tests\Support\FunctionalTester;
use App\User\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserCest
{
    private User $targetUser;

    public function _before(FunctionalTester $I): void
    {
        $I->createUser([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'roles' => ['ROLE_USER_ADMIN'],
        ]);

        $I->createUser([
            'name' => 'otherUser',
            'email' => 'otherUser@other.com',
            'password' => 'other',
            'roles' => ['ROLE_USER'],
        ]);

        $this->targetUser = $I->createUser([
            'name' => 'oldName',
            'email' => 'oldEmail@old.com',
            'password' => 'oldPassword',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function updateUserSuccessfully(FunctionalTester $I): void
    {
        $I->loginAsUser('admin@admin.com', 'admin');

        $updateData = [
            'name' => 'newName',
            'email' => 'newEmail@new.com',
        ];

        $I->sendPut('api/users/'.$this->targetUser->getId(), $updateData);

        $I->seeResponseCodeIs(Response::HTTP_OK);

        $I->seeInRepository(
            User::class,
            [
                'id' => $this->targetUser->getId(),
                'name' => 'newName',
                'email' => 'newEmail@new.com',
            ]
        );

        $I->dontSeeInRepository(
            User::class,
            [
                'id' => $this->targetUser->getId(),
                'name' => 'oldName',
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function updateUserValidationField(FunctionalTester $I): void
    {
        $I->loginAsUser('admin@admin.com', 'admin');

        $invalidData = [
            'name' => '',
            'email' => '',
        ];

        $I->sendPUT('api/users/'.$this->targetUser->getId(), $invalidData);

        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);

        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'field' => 'name',
                    'message' => 'This value should not be blank.',
                ],
                [
                    'field' => 'email',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ]);

        $I->seeInRepository(User::class, [
            'id' => $this->targetUser->getId(),
            'name' => 'oldName',
            'email' => 'oldEmail@old.com',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function updateUserForbiddenForDifferentUser(FunctionalTester $I): void
    {
        $I->loginAsUser('otherUser@other.com', 'other');

        $I->sendPUT(
            'api/users/'.$this->targetUser->getId(),
            [
                'name' => 'newName',
                'email' => 'newEmail@new.com',
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);

        $I->seeInRepository(User::class, [
            'id' => $this->targetUser->getId(),
            'name' => $this->targetUser->getName(),
            'email' => $this->targetUser->getEmail(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function updateUserNotFound(FunctionalTester $I): void
    {
        $I->loginAsUser('admin@admin.com', 'admin');

        $I->sendPUT('api/users/0000');

        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    public function updateUserUnauthorized(FunctionalTester $I): void
    {
        $I->sendPUT('api/users/'.$this->targetUser->getId());

        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);

        $I->seeInRepository(User::class, [
            'id' => $this->targetUser->getId(),
        ]);
    }
}
