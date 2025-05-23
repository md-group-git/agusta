<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ModelRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RideController extends AbstractController
{
    /**
     * @Route("/ride", name="ride")
     * @Template("ride/index.html.twig")
     *
     * @param ModelRepository $modelRepository
     *
     * @return array
     */
    public function index(ModelRepository $modelRepository)
    {
        return [
            'forRide' => $modelRepository->findForRide(),
        ];
    }
}
