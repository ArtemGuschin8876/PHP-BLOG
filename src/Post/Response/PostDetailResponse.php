<?php

namespace App\Post\Response;

class PostDetailResponse
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
