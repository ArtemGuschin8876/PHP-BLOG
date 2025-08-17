<?php

declare(strict_types=1);

namespace App\Shared;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ExceptionListener
{
    private const array EXCEPTION_MAPPING = [
        EntityNotFoundException::class => Response::HTTP_NOT_FOUND,
        InvalidArgumentException::class => Response::HTTP_BAD_REQUEST,
        HttpExceptionInterface::class => null,
    ];

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpExceptionInterface) {
            $previousException = $exception->getPrevious();
            if ($previousException instanceof ValidationFailedException) {
                $this->handleValidationException($event, $previousException);

                return;
            }
        }

        $statusCode = $this->getStatusCodeForException($exception);

        if (Response::HTTP_NOT_FOUND === $statusCode) {
            $response = new JsonResponse([
                'error' => [
                    'code' => $statusCode,
                ],
            ], $statusCode);
        } else {
            $response = new JsonResponse([
                'error' => [
                    'message' => $exception->getMessage(),
                    'code' => $statusCode,
                ],
            ], $statusCode);
        }
        $event->setResponse($response);
    }

    private function handleValidationException(ExceptionEvent $event, ValidationFailedException $validationException): void
    {
        $violations = $validationException->getViolations();
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        $response = new JsonResponse([
            'errors' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        $event->setResponse($response);
    }

    private function getStatusCodeForException(\Throwable $exception): int
    {
        foreach (self::EXCEPTION_MAPPING as $exceptionClass => $defaultStatusCode) {
            if ($exception instanceof $exceptionClass) {
                if ($exception instanceof HttpExceptionInterface) {
                    return $exception->getStatusCode();
                }

                return (int) $defaultStatusCode;
            }
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
