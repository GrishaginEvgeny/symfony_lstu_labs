<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Repository\PostRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository): Response
    {
        //$posts = $postRepository->findOneBy(null,['addDate' => 'DESC']);
        $posts = $postRepository->findAll();
        $params = [
            "menu" => ['Главная','О нас'],
            "posts" => $posts,
        ];

        return $this->render('home/index.html.twig', $params);
    }
}
