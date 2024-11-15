<?php

namespace App\Post\Response;

class PostCreateResponse
{
    public function __construct(
        public string $title,
        public string $content,
        public string $date,
        public int $author,
    ) {
    }
}
