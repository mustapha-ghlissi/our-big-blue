<?php

namespace App\Controller\Admin;

use App\Entity\CapturedData;
use App\Entity\Category;
use App\Entity\Form;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_ADMIN)]
class DashboardController extends AbstractDashboardController
{
    public function __construct()
    {
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('app');
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EntityManagerInterface::class => '?' . EntityManagerInterface::class
        ]);
    }

    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        $entityManager = $this->container->get(EntityManagerInterface::class);
        $users = $entityManager->getRepository(User::class)->getCount();
        $forms = $entityManager->getRepository(Form::class)->getCount();
        $categories = $entityManager->getRepository(Category::class)->getCount();
        $capturedDatas = $entityManager->getRepository(CapturedData::class)->getCount();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'forms' => $forms,
            'categories' => $categories,
            'capturedDatas' => $capturedDatas
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Notre Grand Bleu');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-cubes', Category::class);
        yield MenuItem::linkToCrud('Forms', 'fa-brands fa-wpforms', Form::class);
        yield MenuItem::linkToCrud('Données collectées', 'fa-regular fa-rectangle-list', CapturedData::class);
        yield MenuItem::section('Déconnexion');
        yield MenuItem::linkToLogout('Déconnexion', 'fa-solid fa-right-from-bracket');
    }
}
