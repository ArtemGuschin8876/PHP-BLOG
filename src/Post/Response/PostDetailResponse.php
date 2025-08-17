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

    /**
     * @return array{
     *     id:int,
     *     title:string,
     *     content:string,
     *     date:string,
     *     author:int
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'author' => $this->author,
        ];
    }
}
