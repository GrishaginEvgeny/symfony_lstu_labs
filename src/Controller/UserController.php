<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{token}', name: 'app_user')]
    public function index(string $token): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'token' => $token
        ]);
    }
}
