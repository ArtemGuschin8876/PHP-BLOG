<?php

declare(strict_types=1);

namespace App\User\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueEmail extends Constraint
{
    public string $message = 'Email "{{ value }}" is already exists.';
}
