<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Pages publiques du site vitrine (l'administration se fait via EasyAdmin, voir src/Controller/Admin/).
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

    #[Route('/produits/{id}', name: 'app_produit_detail', methods: ['GET'])]
    public function produitDetail(Produit $produit, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        if (!$produit->isActif()) {
            throw $this->createNotFoundException();
        }

        $similaires = array_values(array_filter(
            $produitRepository->findActifs(),
            fn (Produit $p) => $p->getId() !== $produit->getId() && $p->getCategorie()->getId() === $produit->getCategorie()->getId()
        ));

        return $this->render('main/produit_detail.html.twig', [
            'categories_menu' => $categorieRepository->findAll(),
            'produit' => $produit,
            'produits_similaires' => array_slice($similaires, 0, 3),
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
