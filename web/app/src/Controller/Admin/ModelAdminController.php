<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Model;
use App\Entity\TechSpec;
use App\Enum\StockStatusEnum;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Pix\SortableBehaviorBundle\Controller\SortableAdminController;
use Symfony\Component\HttpFoundation\Request;

final class ModelAdminController extends SortableAdminController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function cloneAction($id)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var Model $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        if (Request::METHOD_PUT === $this->getRestMethod()) {
            $this->validateCsrfToken('sonata.clone');
            $name = $this->escapeHtml($this->admin->toString($object));

            try {
                $cloned = $this->cloneModel($object);

                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans('flash_clone_success', ['%name%' => $name], 'SonataAdminBundle')
                );

                return $this->redirectTo($cloned);
            } catch (Exception $e) {
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans('flash_clone_error', ['%name%' => $name], 'SonataAdminBundle')
                );

                return $this->redirectToList();
            }
        }

        return $this->renderWithExtraParams('admin/action/form_clone.html.twig', [
            'object'     => $object,
            'action'     => 'clone',
            'csrf_token' => $this->getCsrfToken('sonata.clone'),
        ], null);
    }

    public function recalcPositionsAction()
    {
        if (Request::METHOD_PUT === $this->getRestMethod()) {
            $this->validateCsrfToken('sonata.recalcPositions');

            try {
                $this->recalcPositions();

                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans('flash_recalc_positions_success', [], 'SonataAdminBundle')
                );
            } catch (Exception $e) {
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans('flash_recalc_positions_error', [], 'SonataAdminBundle')
                );
            }

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams('admin/action/form_recalc_positions.html.twig', [
            'action'     => 'recalcPositions',
            'csrf_token' => $this->getCsrfToken('sonata.recalcPositions'),
        ], null);
    }

    /**
     * @return object|Model
     */
    private function cloneModel(Model $original): object
    {
        /** @var ModelRepository $repository */
        $repository = $this->entityManager->getRepository(Model::class);

        $cloned = new Model();

        $cloned->setLineup($original->getLineup());
        $cloned->setName($original->getName().' (Copy)');
        $cloned->setPrice($original->getPrice());
        $cloned->setSlogan($original->getSlogan());
        $cloned->setStockStatus(StockStatusEnum::OUT_OF_STOCK);
        $cloned->setSpecial(true);
        $cloned->setRide(false);
        $cloned->setPosition($repository->getLastPosition() + 1);

        $cloned->setModelSpecs($original->getModelSpecs());

        foreach ($original->getTechSpecs() as $originalTechSpec) {
            $clonedTechSpec = new TechSpec();
            $clonedTechSpec->setPosition($originalTechSpec->getPosition());
            $clonedTechSpec->setTechSection($originalTechSpec->getTechSection());
            $clonedTechSpec->setText($originalTechSpec->getText());
            $cloned->addTechSpec($clonedTechSpec);
        }

        return $this->admin->create($cloned);
    }

    private function recalcPositions()
    {
        /** @var ModelRepository $repository */
        $repository = $this->entityManager->getRepository(Model::class);

        $models = [];
        foreach ($repository->findBy([], ['position' => 'asc']) as $model) {
            $models[] = $model;
        }

        $position = 1;
        foreach ($models as $model) {
            $model->setPosition($position++);
            $this->entityManager->flush();
        }
    }
}
