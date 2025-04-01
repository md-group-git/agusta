<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PaintColorAdmin extends AbstractAdmin
{
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
        $sortValues['_sort_by'] = 'name';
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextareaType::class, [
                'label' => 'label.name',
            ])
            ->add('image', ModelListType::class, [
                'label'     => 'label.image',
                'required'  => false,
            ], [
                'link_parameters' => [
                    'context'  => 'color',
                    'provider' => 'sonata.media.provider.svg',
                ],
            ])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('image', null, [
                'label'        => 'label.image',
                'template'     => 'admin/list/image.html.twig',
                'header_style' => 'width: 10%',
                'row_align'    => 'center',
            ])
            ->addIdentifier('name', null, [
                'label'    => 'label.name',
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
            ->add('name', null, [
                'label' => 'label.name',
            ])
        ;
    }
}
