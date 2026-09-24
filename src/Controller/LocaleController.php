<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Le site est maintenant en URLs par langue (/fr/..., /en/..., etc., voir
 * MainController). Le seul rôle restant ici est de rediriger la racine "/"
 * (sans langue) vers la langue déduite du navigateur du visiteur.
 */
final class LocaleController extends AbstractController
{
    #[Route('/', name: 'app_root', methods: ['GET'])]
    public function root(Request $request): Response
    {
        $locale = $request->getPreferredLanguage(['fr', 'en', 'es', 'nl']) ?? 'fr';

        return $this->redirectToRoute('app_accueil', ['_locale' => $locale]);
    }
}
