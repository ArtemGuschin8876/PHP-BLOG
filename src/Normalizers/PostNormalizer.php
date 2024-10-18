<?php

namespace App\Normalizers;

use App\Entity\Post;

class PostNormalizer
{
    public function normalize($object, $format = null,array $context = []):array
    {
    if ($context['mode'] === 'default' && $object instanceof Post ) {
        $postArray = [
            'id' => $object->getId(),
            'title' => $object->getTitle(),
            'content' => $object->getContent(),
        ];
        return $postArray;

    }elseif ($context['mode'] && $context['mode'] !== 'default'){
        return ['message' => 'Неизвестный тип контекста.'];
    }
        return ['message' => 'Не указан mode в контексте.'];
    }
}