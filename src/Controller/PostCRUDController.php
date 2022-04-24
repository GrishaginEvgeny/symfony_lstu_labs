<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostCRUDController extends AbstractController
{
    #[Route('/add', name: 'app_post_c_r_u_d_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post_crud/index.html.twig', [
            'posts' => $postRepository->findBy(['user'=>$this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_post_c_r_u_d_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post);
            return $this->redirectToRoute('app_post_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_crud/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_c_r_u_d_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post_crud/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_c_r_u_d_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post);
            return $this->redirectToRoute('app_post_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_crud/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_c_r_u_d_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute('app_post_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
    }
}
