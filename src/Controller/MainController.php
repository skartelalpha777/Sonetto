<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Pages publiques du site vitrine (pas de partie admin ici, voir ProduitController/CategorieController).
 */
final class MainController extends AbstractController
{
    #[Route('/', name: 'app_accueil', methods: ['GET'])]
    public function accueil(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produitsActifs = $produitRepository->findActifs();

        return $this->render('main/accueil.html.twig', [
            'categories_menu' => $categorieRepository->findAll(),
            // On ne met en avant que les 3 premiers produits sur la page d'accueil.
            'produits_vedette' => array_slice($produitsActifs, 0, 3),
        ]);
    }

    #[Route('/a-propos', name: 'app_apropos', methods: ['GET'])]
    public function apropos(CategorieRepository $categorieRepository): Response
    {
        return $this->render('main/apropos.html.twig', [
            'categories_menu' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/produits', name: 'app_produits', methods: ['GET'])]
    public function produits(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('main/produits.html.twig', [
            'categories_menu' => $categories,
            'categories' => $categories,
            'produits' => $produitRepository->findActifs(),
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(CategorieRepository $categorieRepository): Response
    {
        return $this->render('main/contact.html.twig', [
            'categories_menu' => $categorieRepository->findAll(),
        ]);
    }
}
