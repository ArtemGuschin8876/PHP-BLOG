<?php
declare(strict_types=1);

namespace App\User\Normalizers;

use App\User\Entity\User;

class UserNormalizer
{
    /**
     * @param object               $object
     * @param string|null          $format
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if ('default' === $context['mode'] && $object instanceof User) {
            return [
                'id' => $object->getId(),
                'name' => $object->getName(),
                'email' => $object->getEmail(),
            ];
        } elseif ($context['mode'] && 'default' !== $context['mode']) {
            return ['message' => 'Неизвестный тип контекста.'];
        }

        return ['message' => 'Не указан mode в контексте.'];
    }
}
