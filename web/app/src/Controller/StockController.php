<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ModelRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    /**
     * @Route("/stock", name="stock")
     * @Template("stock/index.html.twig")
     *
     * @param ModelRepository $modelRepository
     *
     * @return array
     */
    public function index(ModelRepository $modelRepository)
    {
        return [
            'inStock' => $modelRepository->findInStock(),
        ];
    }
}
