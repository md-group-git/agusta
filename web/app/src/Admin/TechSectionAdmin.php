<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\TechSectionParentType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TechSectionAdmin extends AbstractAdmin
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
            ->add('name', TextType::class, [
                'label' => 'label.name',
            ])
            ->add('parent', TechSectionParentType::class, [
                'label'    => 'label.section',
                'required' => false,
            ])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, [
                'label'    => 'label.name',
            ])
            ->add('parent', null, [
                'label'    => 'label.section',
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
            ->add('parent', null, [
                'label' => 'label.section',
            ], TechSectionParentType::class)
        ;
    }
}
