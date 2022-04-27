<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/{id}', name: 'app_post', methods: ['GET'])]
    public function index(string $id, PostRepository $postRepository,Request $request): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);
        $author = $post->getUser();
        $comments = $post->getComments();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            return $this->render('post/index.html.twig', [
                'controller_name' => 'PostController',
                'post' => $post,
                'author' => $author,
                'comments' => $comments,
                'commentForm' => $form->createView()
            ]);
        }
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'author' => $author,
            'comments' => $comments,
            'commentForm' => $form->createView()
        ]);
    }

    #[Route('/crud/new', name: 'app_new_post', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post_avatar = $form->get('avatar')->getData();
            $pictures = $form->get('pictures')->getData();

            $originalFilename = pathinfo($post_avatar->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$post_avatar->guessExtension();

            try {
                $post_avatar->move(
                    $this->getParameter('post_pictures'),
                    $newFilename
                );
            } catch (FileException $e) {

            }

            $files_name=[];
            foreach($pictures as $picture) {
                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
                    array_push($files_name, $newFilename);
                    try {
                        $picture->move(
                            $this->getParameter('post_pictures'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                    }
            }

            $post->setAvatar($newFilename);
            $post->setPictures($files_name);
            $post->setAddDate(new \DateTime());
            $post->setViewCount(0);
            $post->setUser($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/crud/add.html.twig',['postAdd' => $form->createView(),]);
    }
}
