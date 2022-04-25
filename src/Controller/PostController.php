<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostType;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/{id}', name: 'app_post', methods: ['GET'])]
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

    #[Route('/crud/new', name: 'app_new_post', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$postRepository->add($post);
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/crud/add.html.twig',['postAdd' => $form->createView(),]);
    }
}
