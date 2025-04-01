<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\ModelType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderRequestAdmin extends ClientRequestAdmin
{
    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        parent::configureShowFields($show);

        $show
            ->add('email', 'email')
            ->add('model', null, [
                'label' => 'label.model',
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('label.request', ['class' => 'col-md-4'])
                ->add('model', ModelType::class, [
                    'label'    => 'label.model',
                    'required' => false,
                ])
            ->end()
            ->with('label.contacts')
                ->add('email', TextType::class, [
                    'required' => false,
                ])
            ->end()
        ;

        parent::configureFormFields($formMapper);
    }

    /**
     * @param ListMapper $list
     */
    protected function configureCustomListFields(ListMapper $list)
    {
        $list
            ->add('email')
            ->add('model', null, [
                'label' => 'label.model',
            ])
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('email')
            ->add('model', null, [
                'label' => 'label.model',
            ])
        ;
    }
}
