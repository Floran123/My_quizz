<?php

namespace App\Controller\Admin;

use App\Controller\IndexController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
        // return parent::index();,
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My Quizz');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Index', 'fas fa-home', "index"),
            MenuItem::linktoDashboard('Dashboard', 'fa fa-list'),
        ];
    }
}
