<?php

declare(strict_types=1);

namespace App\Post\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueTitle extends Constraint
{
    public string $message = 'Title "{{ value }}" is already exists.';
}
