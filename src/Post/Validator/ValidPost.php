<?php

declare(strict_types=1);

namespace App\Post\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidPost extends Constraint
{
    public string $message = 'Post is not valid';

    public function getTargets(): array
    {
        return [self::CLASS_CONSTRAINT];
    }
}
