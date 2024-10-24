<?php

declare(strict_types=1);

namespace App\Normalizers;

use App\Entity\Post;

class PostNormalizer
{
    public function normalize($object, $format = null, array $context = []): array
    {
        if ('default' === $context['mode'] && $object instanceof Post) {
            $postArray = [
                'id' => $object->getId(),
                'title' => $object->getTitle(),
                'content' => $object->getContent(),
                'time' => $object->getCreatedAt()->format('Y-m-d H:i:s'),
            ];

            return $postArray;
        } elseif ($context['mode'] && 'default' !== $context['mode']) {
            return ['message' => 'Неизвестный тип контекста.'];
        }

        return ['message' => 'Не указан mode в контексте.'];
    }
}
