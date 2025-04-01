<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\RequestStatusType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

abstract class ClientRequestAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    public function getBatchActions(): array
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
        $sortValues['_sort_order'] = 'DESC';
        $sortValues['_sort_by'] = 'createdAt';
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('status', null, [
                'label'    => 'label.status',
                'template' => 'admin/list/request_status.html.twig',
            ])
            ->add('id', null, [
                'label' => 'label.num',
            ])
            ->add('referer', 'url', [
                'label'      => 'label.page',
                'attributes' => ['target' => '_blank'],
            ])
            ->add('createdAt', 'datetime', [
                'label'  => 'label.created_at',
                'format' => 'd.m.Y H:i:s',
            ])
            ->add('updatedAt', 'datetime', [
                'label'  => 'label.updated_at',
                'format' => 'd.m.Y H:i:s',
            ])
            ->add('firstName', null, [
                'label' => 'label.first_name',
            ])
            ->add('lastName', null, [
                'label' => 'label.last_name',
            ])
            ->add('phone', null, [
                'label' => 'label.phone',
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('label.contacts', ['class' => 'col-md-3'])
                ->add('phone', TextType::class, [
                    'label'    => 'label.phone',
                    'required' => true,
                ])
                ->add('firstName', TextType::class, [
                    'label'    => 'label.first_name',
                    'required' => true,
                ])
                ->add('lastName', TextType::class, [
                    'label'    => 'label.last_name',
                    'required' => true,
                ])
            ->end()
        ;

        $status = $formMapper->with('label.status', ['class' => 'col-md-5']);

        if ($this->hasSubject() && $this->getSubject()->getId()) {
            $status
                ->add('createdAt', DateTimePickerType::class, [
                    'label'    => 'label.created_at',
                    'disabled' => true,
                    'required' => false,
                ])
                ->add('updatedAt', DateTimePickerType::class, [
                    'label'    => 'label.updated_at',
                    'disabled' => true,
                    'required' => false,
                ])
            ;
        }

        $status
            ->add('status', RequestStatusType::class, [
                'label' => 'label.status',
            ])
            ->add('referer', TextType::class, [
                'label'    => 'label.page',
                'required' => false,
            ])
        ;

        $status->end();
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', null, [
                'label'        => 'label.num',
                'header_style' => 'width: 5%',
                'row_align'    => 'right',
            ])
            ->add('createdAt', 'datetime', [
                'label'        => 'label.created_at',
                'format'       => 'd.m.Y H:i:s',
                'header_style' => 'width: 10%',
                'row_align'    => 'center',
            ])
            ->add('status', null, [
                'label'        => 'label.status',
                'template'     => 'admin/list/request_status.html.twig',
                'sortable'     => false,
                'header_style' => 'width: 5%',
                'row_align'    => 'center',
            ])
//            ->add('referer', null, [
//                'label' => 'label.page',
//            ])
            ->add('firstName', null, [
                'label' => 'label.first_name',
            ])
            ->add('lastName', null, [
                'label' => 'label.last_name',
            ])
            ->add('phone', null, [
                'label' => 'label.phone',
            ])
        ;

        $this->configureCustomListFields($list);

        $list
            ->add('_action', 'actions', [
                'label'   => 'label.action',
                'actions' => [
                    'view'   => [],
                    'edit'   => [],
                    'delete' => [],
                ],
                'header_style' => 'width: 15%',
                'row_align'    => 'center',
            ])
        ;
    }

    protected function configureCustomListFields(ListMapper $list)
    {
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => 'label.num',
            ])
            ->add('createdAt', null, [
                'label' => 'label.created_at',
            ])
            ->add(
                'status',
                'doctrine_orm_string',
                ['label' => 'label.status'],
                RequestStatusType::class
            )
            ->add('firstName', null, [
                'label' => 'label.first_name',
            ])
            ->add('lastName', null, [
                'label' => 'label.last_name',
            ])
            ->add('phone', null, [
                'label' => 'label.phone',
            ])
        ;
    }

    /**
     * @return bool
     */
    protected function isCreated(): bool
    {
        return $this->hasSubject() && $this->getSubject()->getId();
    }
}
