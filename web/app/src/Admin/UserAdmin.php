<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdmin extends AbstractAdmin
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @required
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function setEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        /* @var User $object */
        $object->setRoles(['ROLE_ADMIN']);
        $this->updatePassword($object);

        parent::prePersist($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        /* @var User $object */
        $this->updatePassword($object);

        parent::preUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues['_page'] = 1;
        $sortValues['_sort_order'] = 'ASC';
        $sortValues['_sort_by'] = 'email';
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $created = $this->hasSubject() && $this->getSubject()->getId();

        $formMapper
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('plainPassword', TextType::class, [
                'label'    => 'label.password',
                'required' => !$created,
            ])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email', null, [
                'label'    => 'label.email',
            ])
            ->add('_action', 'actions', [
                'label'   => 'label.action',
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
                'header_style' => 'width: 15%',
                'row_align'    => 'center',
            ])
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email', null, [
                'label' => 'label.email',
            ])
        ;
    }

    /**
     * @param User $user
     */
    private function updatePassword(User $user)
    {
        $password = $user->getPlainPassword();
        if ($password) {
            $user->setPassword($this->encoder->encodePassword($user, $password));
        }
    }
}
