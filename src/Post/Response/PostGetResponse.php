<?php

namespace App\Post\Response;

class PostGetResponse
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public string $date,
        public int $author,
    ) {
    }
}
