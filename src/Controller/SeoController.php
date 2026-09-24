<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * robots.txt et sitemap.xml, générés dynamiquement pour toujours pointer
 * vers le bon domaine et rester à jour avec les produits actifs.
 */
final class SeoController extends AbstractController
{
    #[Route('/robots.txt', name: 'app_robots', methods: ['GET'])]
    public function robots(UrlGeneratorInterface $urlGenerator): Response
    {
        $sitemapUrl = $urlGenerator->generate('app_sitemap', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $content = <<<TXT
        User-agent: *
        Disallow: /admin
        Disallow: /login
        Disallow: /reset-password
        Allow: /

        Sitemap: {$sitemapUrl}
        TXT;

        return new Response($content, 200, ['Content-Type' => 'text/plain']);
    }

    #[Route('/sitemap.xml', name: 'app_sitemap', methods: ['GET'])]
    public function sitemap(ProduitRepository $produitRepository, UrlGeneratorInterface $urlGenerator): Response
    {
        $locales = ['fr', 'en', 'es', 'nl'];

        $pages = [
            ['route' => 'app_accueil', 'priority' => '1.0'],
            ['route' => 'app_produits', 'priority' => '0.9'],
            ['route' => 'app_apropos', 'priority' => '0.6'],
            ['route' => 'app_contact', 'priority' => '0.6'],
        ];

        $urls = [];
        foreach ($locales as $locale) {
            foreach ($pages as $page) {
                $urls[] = [
                    'loc' => $urlGenerator->generate($page['route'], ['_locale' => $locale], UrlGeneratorInterface::ABSOLUTE_URL),
                    'priority' => $page['priority'],
                ];
            }

            foreach ($produitRepository->findActifs() as $produit) {
                $urls[] = [
                    'loc' => $urlGenerator->generate('app_produit_detail', ['_locale' => $locale, 'id' => $produit->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'priority' => '0.8',
                ];
            }
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        foreach ($urls as $url) {
            $entry = $xml->addChild('url');
            $entry->addChild('loc', htmlspecialchars($url['loc']));
            $entry->addChild('priority', $url['priority']);
        }

        return new Response($xml->asXML(), 200, ['Content-Type' => 'application/xml']);
    }
}
