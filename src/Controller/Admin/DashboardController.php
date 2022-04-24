<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
       $commentUrl = $adminUrlGenerator->setController(CommentCrudController::class)->generateUrl();
       $postUrl = $adminUrlGenerator->setController(PostCrudController::class)->generateUrl();

        //return parent::index();

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('dashboard/index.html.twig',[
            'postUrl' => $postUrl,
            'commentUrl' => $commentUrl,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PMBloGG');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoUrl('На домашнюю страницу', 'fas fa-home', '/'),
            MenuItem::linkToCrud('Комментарии', 'fa-solid fa-comment', Comment::class),
            MenuItem::linkToCrud('Посты', 'fa-solid fa-newspaper', Post::class),
        ];
    }
}
