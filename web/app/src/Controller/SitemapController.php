<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends AbstractController
{
//    /**
//     * @Route("/sitemap.{format}", name="sitemap_index", requirements={"format" = "xml"})
//     */
//    public function index(): Response
//    {
//        $sitemaps = [
//            $this->getSitemapIndexUrl('sitemap_main'),
//            $this->getSitemapIndexUrl('sitemap_stock'),
//        ];
//
//        return $this->getSitemapIndex($sitemaps);
//    }

    /**
     * @Route("/sitemap-main.{format}", name="sitemap_main", requirements={"format" = "xml"})
     */
    public function main(): Response
    {
        $urlSet = [
            $this->getSitemapUrl('homepage'),
            $this->getSitemapUrl('stock'),
            $this->getSitemapUrl('ride'),
            $this->getSitemapUrl('service'),
            $this->getSitemapUrl('company'),
            $this->getSitemapUrl('contacts'),
        ];

        return $this->getUrlSet($urlSet);
    }

    /**
     * @Route("/sitemap-stock.{format}", name="sitemap_stock", requirements={"format" = "xml"})
     */
    public function stock(ModelRepository $modelRepository): Response
    {
        $urlSet = [];

        foreach ($modelRepository->findInStock() as $model) {
            $urlSet[] = $this->getSitemapUrl('model', '0.9', [
                'lineup' => $model->getLineup()->getSlug(),
                'model'  => $model->getSlug(),
            ]);
        }

        return $this->getUrlSet($urlSet);
    }

    private function getSitemapIndexUrl(string $route): array
    {
        $referenceType = UrlGeneratorInterface::ABSOLUTE_URL;

        return [
            'loc' => $this->generateUrl($route, ['format' => 'xml'], $referenceType),
        ];
    }

    private function getSitemapUrl(string $route, string $priority = '1.0', array $parameters = []): array
    {
        $referenceType = UrlGeneratorInterface::ABSOLUTE_URL;

        return [
            'loc'      => $this->generateUrl($route, $parameters, $referenceType),
            'priority' => $priority,
        ];
    }

    private function getUrlSet(array $urlSet): Response
    {
        return $this->getXml('sitemap/urlset.xml.twig', ['urlSet' => $urlSet]);
    }

    private function getSitemapIndex(array $sitemaps): Response
    {
        return $this->getXml('sitemap/index.xml.twig', ['sitemaps' => $sitemaps]);
    }

    private function getXml(string $view, array $data = []): Response
    {
        $content = $this->renderView($view, $data);

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/xml']);
    }
}
