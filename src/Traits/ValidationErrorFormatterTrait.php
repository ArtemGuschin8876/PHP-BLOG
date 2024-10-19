<?php
namespace App\Traits;

use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ValidationErrorFormatterTrait
{
    public function formatValidationErrors(ConstraintViolationListInterface $violations):array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }
        return $errors;
    }
}