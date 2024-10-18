<?php

namespace App\Controller;

use App\Normalizers\PostNormalizer;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private PostService $postService;
    private PostNormalizer $normalizer;

    public function __construct(PostService $postService, PostNormalizer $normalizer)
    {
        $this->normalizer= $normalizer;
        $this->postService = $postService;
    }

    #[Route('/posts', name: 'app_posts', methods:['GET'])]
    public function list(PostRepository $postRepository): Response
    {
        $posts = $this->postService->getAllPosts();

        $normalizedPosts = array_map(function ($post) {
            return $this->normalizer->normalize($post, null, ['mode' => 'default']);
        }, $posts);
        return $this->json(['posts' => $normalizedPosts], Response::HTTP_OK);
    }

    #[Route('/post/{id}', name : 'app_post_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $post = $this->postService->findPostById($id);

        if (!$post){
           return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $normalizedPost = $this->normalizer->normalize($post,
            null,
            ['mode' => 'default']);

        return $this->json($normalizedPost, Response::HTTP_OK);
    }

    #[Route('/create',  name: 'app_post_create',methods:['POST'])]
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
        ], Response::HTTP_CREATED);
    }

    #[Route('/post/update/{id}', name: 'app_post_update', methods: ['PUT'])]
    public function updatePostByID(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $this->postService->updatePostByID($id, $data);
            return new JsonResponse(['message'=>'Post updated successfully'], Response::HTTP_OK);
        }catch (\Exception $exception){
            return new JsonResponse(['error'=>$exception->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/post/delete/{id}', name: 'app_post_delete', methods:['DELETE'])]
    public function deletePostByID(int $id): JsonResponse
    {
        try {
            $this->postService->deletePost($id);
        }catch (\Exception $exception){
            return new JsonResponse(['error'=>$exception->getMessage()],
                Response::HTTP_NOT_FOUND);
        }
       return new JsonResponse([
           'status'=>'success',
           'id'=>$id,
       ]);
    }


}
