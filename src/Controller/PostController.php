<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post')]
    public function index(string $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);
        $author = $post->getUser();
        $comments = $post->getComments();
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'author' => $author,
            'comments' => $comments
        ]);
    }
}
