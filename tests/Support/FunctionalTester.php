<?php

declare(strict_types=1);

namespace App\Tests\Support;

use App\User\Entity\User;

/**
 * Inherited Methods.
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method User createUser(array $data)
 * @method void pause($vars = [])
 * @method void loginAsUser(string $email, string $password)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /*
     * Define custom actions here
     */
}
