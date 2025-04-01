<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\ModelType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RideRequestAdmin extends ClientRequestAdmin
{
    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        parent::configureShowFields($show);

        $show
            ->add('email', 'email')
            ->add('date', 'datetime', [
                'label'  => 'label.date',
                'format' => 'd.m.Y h:i',
            ])
            ->add('model', null, [
                'label' => 'label.model',
            ])
            ->add('licensed', null, [
                'label' => 'label.licensed',
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
                ->add('date', DateTimeType::class, [
                    'label'    => 'label.date',
                    'required' => true,
                ])
                ->add('model', ModelType::class, [
                    'label'    => 'label.model',
                    'required' => false,
                ])
                ->add('licensed', CheckboxType::class, [
                    'label'    => 'label.licensed',
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
            ->add('date', 'datetime', [
                'label'        => 'label.date',
                'format'       => 'd.m.Y h:i',
                'header_style' => 'width: 10%',
            ])
            ->add('licensed', null, [
                'label'        => 'label.licensed',
                'header_style' => 'width: 5%',
                'row_align'    => 'center',
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
            ->add('date', null, [
                'label' => 'label.date',
            ])
            ->add('licensed', null, [
                'label' => 'label.licensed',
            ])
        ;
    }
}
