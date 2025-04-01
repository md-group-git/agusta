<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends AbstractController
{
    /**
     * @Route("/contacts", name="contacts")
     * @Template("contacts/index.html.twig")
     *
     * @return array
     */
    public function index()
    {
        return [
            'controller_name' => 'ContactsController',
        ];
    }
}
