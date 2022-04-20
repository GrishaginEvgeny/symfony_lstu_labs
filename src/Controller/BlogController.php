<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Post;
use App\Repository\UserRepository;

class BlogController extends AbstractController
{
    #[Route('/blog/{name}', name: 'app_blog')]
    public function index(string $name, UserRepository $userRepository): Response
    {
        $blog = $userRepository->findOneBy(
            ['blog_name' => $name]
        );

        $posts = $blog->getPosts();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'blog' => $blog,
            'posts' => $posts
        ]);
    }
}
