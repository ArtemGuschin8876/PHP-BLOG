<?php

namespace App\Post\Response;

class PostDetailResponse
{
    public function __construct(
        private int $id,
        private string $title,
        private string $content,
        private string $date,
        private int $author,
    )
    {
    }
}