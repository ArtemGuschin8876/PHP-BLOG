<?php

namespace App\Controller;
use APP\Entity\Post;
use App\DTO\CreatePostDTO;
use App\DTO\UpdatePostDTO;
use App\Normalizers\PostNormalizer;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class PostController extends AbstractController
{
    private PostService $postService;
    private PostNormalizer $normalizer;


    public function __construct(PostService $postService, PostNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
        $this->postService = $postService;
    }

    #[Route('/posts', name: 'posts', methods: ['GET'])]
    public function list(PostRepository $postRepository): Response
    {
        $posts = $this->postService->getAllPosts();

        $normalizedPosts = array_map(function ($post) {
            return $this->normalizer->normalize(
                $post,
                null,
                ['mode' => 'default']);
        }, $posts);
        return $this->json(['posts' => $normalizedPosts], Response::HTTP_OK);
    }

    #[Route('/post/{id}', name: 'post_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $post = $this->postService->findPostById($id);

        if (!$post) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $normalizedPost = $this->normalizer->normalize(
            $post,
            null,
            ['mode' => 'default']);

        return $this->json($normalizedPost, Response::HTTP_OK);
    }

    #[Route('/create', name: 'post_create', methods: ['POST'])]
    public function createPost(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $CreatePostDTO = new CreatePostDTO($data);

        $result = $this->postService->createPost($CreatePostDTO);

        return new JsonResponse($result);

    }

    #[Route('/post/update/{id}', name: 'post_update',methods:['PUT'])]
    public function updatePostByID(Request $request, int $id): JsonResponse
    {
       $data = json_decode($request->getContent(), true);
        $UpdatePostDTO = new UpdatePostDTO($data);

        $result = $this->postService->updatePostById($id, $UpdatePostDTO);
        return new JsonResponse($result);
    }

    #[Route('/post/delete/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function deletePostByID(Post $post): JsonResponse
    {
        $this->postService->deletePost($post);
        return new JsonResponse(['status' => 'Post deleted']);
    }
}



