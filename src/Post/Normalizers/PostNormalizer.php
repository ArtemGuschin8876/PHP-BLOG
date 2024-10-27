<?php

declare(strict_types=1);

namespace App\Post\Normalizers;

use App\Post\Entity\Post;

class PostNormalizer
{
    /**
     * @param object               $object
     * @param string|null          $format
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if ('default' === $context['mode'] && $object instanceof Post) {
            return [
                'id' => $object->getId(),
                'author' => $object->getAuthor(),
                'title' => $object->getTitle(),
                'content' => $object->getContent(),
                'time' => $object->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        } elseif ($context['mode'] && 'default' !== $context['mode']) {
            return ['message' => 'Неизвестный тип контекста.'];
        }

        return ['message' => 'Не указан mode в контексте.'];
    }
}
