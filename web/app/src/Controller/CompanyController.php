<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     * @Template("company/index.html.twig")
     *
     * @return array
     */
    public function index()
    {
        return [
            'controller_name' => 'CompanyController',
        ];
    }
}
