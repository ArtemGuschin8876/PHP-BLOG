<?php

namespace App\Post\Response;

class PostUpdateResponse
{
    public function __construct(
        public string $title,
        public string $content,
    ) {
    }
}
