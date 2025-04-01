<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CallRequestAdmin extends ClientRequestAdmin
{
    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        parent::configureShowFields($show);

        $show
            ->add('message', null, [
                'label' => 'label.message',
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
                ->add('message', TextareaType::class, [
                    'label'    => 'label.message',
                    'required' => false,
                ])
            ->end()
        ;

        parent::configureFormFields($formMapper);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('message', null, [
                'label' => 'label.message',
            ])
        ;
    }
}
