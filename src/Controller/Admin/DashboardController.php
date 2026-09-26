<?php

namespace App\Controller\Admin;

use App\Repository\CategorieRepository;
use App\Repository\ImageProduitRepository;
use App\Repository\ProduitRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly ProduitRepository $produitRepository,
        private readonly CategorieRepository $categorieRepository,
        private readonly ImageProduitRepository $imageProduitRepository,
    ) {
    }

    /**
     * Page d'accueil du back-office : quelques KPIs simples (produits actifs,
     * catégories, photos en galerie) et des raccourcis vers le site public.
     */
    public function index(): Response
    {
        $produits = $this->produitRepository->findAll();
        $produitsActifs = array_filter($produits, fn ($p) => $p->isActif());

        return $this->render('admin/dashboard.html.twig', [
            'kpis' => [
                [
                    'label' => 'Produits actifs',
                    'icon' => 'fa-camera',
                    'color' => '#2563eb',
                    'value' => count($produitsActifs),
                ],
                [
                    'label' => 'Produits au total',
                    'icon' => 'fa-boxes-stacked',
                    'color' => '#0b1e3d',
                    'value' => count($produits),
                ],
                [
                    'label' => 'Catégories',
                    'icon' => 'fa-tags',
                    'color' => '#22c55e',
                    'value' => count($this->categorieRepository->findAll()),
                ],
                [
                    'label' => 'Photos en galerie',
                    'icon' => 'fa-images',
                    'color' => '#f97316',
                    'value' => count($this->imageProduitRepository->findAll()),
                ],
            ],
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sonetto <span class="badge bg-primary">Admin</span>')
            ->setFaviconPath('images/logo.svg');
    }

    /**
     * Configuration EasyAdmin du menu latéral : entrées CRUD regroupées par section,
     * plus un lien de retour vers le site public.
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkTo(ProduitCrudController::class, 'Produits', 'fas fa-video')->setAction(Action::INDEX);
        yield MenuItem::linkTo(CategorieCrudController::class, 'Catégories', 'fas fa-tags')->setAction(Action::INDEX);
        yield MenuItem::linkTo(ImageProduitCrudController::class, 'Galerie photos', 'fas fa-images')->setAction(Action::INDEX);

        yield MenuItem::section('Traductions');
        yield MenuItem::linkTo(ProduitTranslationCrudController::class, 'Traductions de produits', 'fas fa-language')->setAction(Action::INDEX);
        yield MenuItem::linkTo(CategorieTranslationCrudController::class, 'Traductions de catégories', 'fas fa-language')->setAction(Action::INDEX);

        yield MenuItem::section('Administration');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fas fa-user-shield')->setAction(Action::INDEX);

        yield MenuItem::section();
        yield MenuItem::linkToUrl('Voir le site', 'fas fa-arrow-up-right-from-square', '/');
        yield MenuItem::linkToLogout('Déconnexion', 'fas fa-sign-out-alt');
    }
}
