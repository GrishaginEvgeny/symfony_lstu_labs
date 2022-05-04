<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\DBAL\Driver\PDO\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/{id}', name: 'app_post', methods: ['GET', 'POST'])]
    public function index(string $id, PostRepository $postRepository,EntityManagerInterface $entityManager, CommentRepository $commentRepository ,Request $request): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);
        $author = $post->getUser();
        $comments = $commentRepository->findBy(['isModerated' => true, 'id' => $id], ['addDate' => 'DESC']);
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_coment = new Comment();
            $new_coment->setUser($this->getUser());
            $new_coment->setAddDate(new \DateTime('now'));
            $new_coment->setIsModerated(false);
            $new_coment->setPost($post);
            $new_coment->setText($form->get('text')->getData());
            $reply_id = $form->get('reply_id')->getData();
            $reply = $commentRepository->findOneBy(['id'=>$reply_id]);
            $new_coment->setReply($reply);

            $entityManager->persist($new_coment);
            $entityManager->flush();
            return $this->redirect('/');
        }
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'author' => $author,
            'comments' => $comments,
            'commentForm' => $form->createView(),
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
