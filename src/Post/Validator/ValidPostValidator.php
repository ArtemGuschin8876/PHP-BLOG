<?php

namespace App\Post\Validator;
use App\Post\Repository\PostRepository;
use App\Post\Request\CreatePostDTO;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidPostValidator extends ConstraintValidator
{
    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {

        if (!$value instanceof CreatePostDTO) {
            throw new UnexpectedTypeException($constraint, CreatePostDTO::class);
        }

        if (empty($value->title)) {
            $this->context->buildViolation('Title must not be blank.')
                ->atPath('title')
                ->addViolation();
        }


        if (empty($value->getAuthor())) {
            $this->context->buildViolation('Author cannot be empty')
            ->atPath('author')
            ->addViolation();
        }

        if (empty($value->getContent())) {
            $this->context->buildViolation('Content cannot be empty')
                ->atPath('content')
                ->addViolation();
        }

    }
}
