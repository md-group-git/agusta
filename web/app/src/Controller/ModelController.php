<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Model;
use App\Repository\ModelRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
{
    /**
     * @Route("/moto/{lineup}/{model}", name="model")
     *
     * @ParamConverter("model", class="App\Entity\Model", options={
     *  "repository_method" = "findOneByLineupAndModel",
     *  "mapping": {"lineup": "lineup", "model": "model"},
     *  "map_method_signature" = true
     * })
     *
     * @Template("model/index.html.twig")
     *
     * @param Model           $model
     * @param ModelRepository $modelRepository
     *
     * @return array
     */
    public function index(Model $model, ModelRepository $modelRepository)
    {
        return [
            'model'   => $model,
            'inStock' => $modelRepository->findInStock($model),
        ];
    }
}
