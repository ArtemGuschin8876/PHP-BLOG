<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{

    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }


    #[Route('/posts', name: 'app_posts', methods:['GET'])]
    public function list(): JsonResponse
    {
        

    }


    #[Route('/create', methods:['POST'])]
    public function createPost(Request $request): JsonResponse
    {   
        $data = json_decode($request->getContent(), true);

        $post = $this->postService->createPost($data['title'], $data['content']);

        return new JsonResponse([
            'status'=>'success',
            'message'=>'Post created successfully',
            'post'=>[
                'id'=>$post->getId(),
                'title'=>$post->getTitle(),
                'content'=>$post->getContent(),
            ]
            ], JsonResponse::HTTP_CREATED);
    }

}
