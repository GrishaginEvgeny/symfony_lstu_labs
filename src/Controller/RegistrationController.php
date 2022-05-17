<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use App\Security\UserAuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager,
                             SluggerInterface $slugger,
                             CategoryRepository $categoryRepository,
                             UserAuthAuthenticator $authenticator,
                             UserAuthenticatorInterface $userAuthenticator,
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $user = new User();
        $allCategories = $categoryRepository->findAll();
        $result = [];
        foreach($allCategories as $category){
            $result[$category->getName()] = $category;
        }
        $form = $this->createForm(RegistrationFormType::class, $user, ['categories' => $result]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user_avatar = $form->get('user_avatar')->getData();
            $blog_picture = $form->get('blog_picture')->getData();

            if($user_avatar !== null & $blog_picture !== null) {
                $files = [
                    ['file' => $user_avatar, 'directory' => 'user_avatars', 'setMethod' => 'setUserAvatar'],
                    ['file' => $blog_picture, 'directory' => 'blog_avatars', 'setMethod' => 'setBlogPicture'],

                ];
                foreach ($files as $file) {
                    if ($file['file']) {
                        $originalFilename = pathinfo($file['file']->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file['file']->guessExtension();

                        try {
                            $file['file']->move(
                                $this->getParameter($file['directory']),
                                $newFilename
                            );
                        } catch (FileException $e) {

                        }
                        $user->{$file['setMethod']}($newFilename);
                    }
                }
            }

            $user->setApiToken(Uuid::v1()->toRfc4122());


            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
