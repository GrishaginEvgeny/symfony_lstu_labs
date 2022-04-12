<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $categories = [
            'В мире' => 'В мире',
            'Россия' => 'Россия',
            'Технологии' => 'Технологии',
            'Дизайн' => 'Дизайн',
            'Культура' => 'Культура',
            'Бизнес'=> 'Бизнес',
            'Политика' => 'Политика',
            'IT'=> 'IT',
            'Наука'=> 'Наука',
            'Здоровье'=> 'Здоровье',
            'Спорт' => 'Спорт',
            'Путешествия' => 'Путешествия'];
        $form = $this->createForm(RegistrationFormType::class, $user, ['categories' => $categories]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            //$entityManager->persist($user);
            //$entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
