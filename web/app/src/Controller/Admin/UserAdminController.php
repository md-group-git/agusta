<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;

final class UserAdminController extends CRUDController
{
    /**
     * {@inheritdoc}
     */
    public function deleteAction($id)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var User $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($user->getId() === $object->getId()) {
            $this->addFlash(
                'sonata_flash_error',
                $this->trans('message.you_cannot_delete_your_own_account')
            );

            return $this->redirectTo($object);
        }

        return parent::deleteAction($id);
    }
}
