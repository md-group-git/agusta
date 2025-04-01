<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Model;
use App\Form\LineupType;
use App\Form\StockStatusType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModelAdmin extends AbstractAdmin
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
    public function prePersist($object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $modelRepository = $container->get('doctrine')->getRepository(Model::class);

        /* @var Model $object */
        $object->setPosition($modelRepository->getLastPosition() + 1);

        parent::prePersist($object);
    }

    public function configureActionButtons($action, $object = null): array
    {
        $list = parent::configureActionButtons($action, $object);
        $list['recalcPositions'] = ['template' => 'admin/action/recalc_positions.html.twig'];

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues['_page'] = 1;
        $sortValues['_sort_order'] = 'ASC';
        $sortValues['_sort_by'] = 'position';
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
        $collection->add('clone', $this->getRouterIdParameter().'/clone');
        $collection->add('recalcPositions');
    }

    protected function configureFormFields(FormMapper $form)
    {
        $tab = $form->with('label.main', ['tab' => true])
            ->with('label.model', ['class' => 'col-md-6'])
                ->add('lineup', LineupType::class, [
                    'label'         => 'label.lineup',
                    'required'      => true,
                ])
                ->add('name', TextType::class, [
                    'label' => 'label.name',
                ])
                ->add('slogan', TextType::class, [
                    'label'    => 'label.slogan',
                    'required' => false,
                ])
            ->end()
            ->with('label.availability', ['class' => 'col-md-6'])
                ->add('price', NumberType::class, [
                    'label' => 'label.price',
                ], ['precision' => 2, 'grouping' => true])
                ->add('stockStatus', StockStatusType::class, [
                    'label' => 'label.stock_status',
                ])
                ->add('special', CheckboxType::class, [
                    'label'    => 'label.special',
                    'required' => false,
                ])
                ->add('ride', CheckboxType::class, [
                    'label'    => 'label.test_ride',
                    'required' => false,
                ])
            ->end();
        $tab->end();

        $tab = $form->with('label.media', ['tab' => true])
            ->with('label.add_media')
                ->add('logo', ModelListType::class, [
                    'label'     => 'label.logo',
                    'required'  => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'logo',
                        'provider' => 'sonata.media.provider.svg',
                    ],
                ])
                ->add('image', ModelListType::class, [
                    'label'     => 'label.image',
                    'required'  => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'model',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ])
                ->add('header', ModelListType::class, [
                    'label'    => 'label.header_gallery',
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'header_gallery',
                    ],
                ])
                ->add('gallery', ModelListType::class, [
                    'label'    => 'label.bike_gallery',
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'context' => 'bike_gallery',
                    ],
                ])
                ->add('sound', ModelListType::class, [
                    'label'    => 'label.engine_sound',
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'sound',
                        'provider' => 'sonata.media.provider.audio',
                    ],
                ])
                ->add('brochure', ModelListType::class, [
                    'label'    => 'label.brochure',
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'document',
                        'provider' => 'sonata.media.provider.file',
                    ],
                ])
            ->end();
        $tab->end();

        $tab = $form->with('label.specs', ['tab' => true])
            ->with('label.common', ['class' => 'col-md-6'])
                ->add('speed', NumberType::class, [
                    'label'    => 'label.speed',
                    'required' => false,
                ])
                ->add('weight', NumberType::class, [
                    'label'    => 'label.weight',
                    'required' => false,
                ])
            ->end()
            ->with('label.engine', ['class' => 'col-md-6'])
                ->add('cylinders', TextType::class, [
                    'label'    => 'label.cylinders',
                    'required' => false,
                ])
                ->add('volume', TextType::class, [
                    'label'    => 'label.volume_cc',
                    'required' => false,
                ])
                ->add('power', TextType::class, [
                    'label'    => 'label.power',
                    'required' => false,
                ])
                ->add('powerRpm', TextType::class, [
                    'label'    => 'label.power_rpm',
                    'required' => false,
                ])
                ->add('compression', TextType::class, [
                    'label'    => 'label.compression',
                    'required' => false,
                ])
                ->add('torque', TextType::class, [
                    'label'    => 'label.torque',
                    'required' => false,
                ])
                ->add('torqueRpm', TextType::class, [
                    'label'    => 'label.torque_rpm',
                    'required' => false,
                ])
            ->end();
        $tab->end();

        $tab = $form->with('label.tech_specs', ['tab' => true])
            ->with('label.add_tech_specs')
                ->add('techSpecs', CollectionType::class, [
                    'label'        => 'label.tech_specs',
                    'by_reference' => false,
                ], [
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                ])
            ->end();
        $tab->end();

        $tab = $form->with('label.paints', ['tab' => true])
            ->with('label.add_paints')
                ->add('paints', CollectionType::class, [
                    'label'        => 'label.paints',
                    'type_options' => [
                        'delete' => true,
                    ],
                ], [
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                ])
            ->end();
        $tab->end();

        $tab = $form->with('label.highlights', ['tab' => true])
            ->with('label.add_highlights')
                ->add('highlights', CollectionType::class, [
                    'label'        => 'label.highlights',
                    'type_options' => [
                        'delete' => true,
                    ],
                ], [
                    'edit'     => 'inline',
                    'sortable' => 'position',
                ])
            ->end();
        $tab->end();
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
//            ->addIdentifier('image', null, [
//                'label'        => 'label.image',
//                'template'     => 'admin/list/image.html.twig',
//                'header_style' => 'width: 10%',
//                'row_align'    => 'center',
//            ])
            ->add('lineup', null, [
                'label'        => 'label.lineup',
                'header_style' => 'width: 10%',
            ])
            ->addIdentifier('name', null, [
                'label'    => 'label.name',
                'sortable' => false,
            ])
            ->add('price', null, [
                'label'        => 'label.price',
                'template'     => 'admin/list/price.html.twig',
                'header_style' => 'width: 10%',
                'row_align'    => 'right',
            ])
            ->add('stockStatus', null, [
                'label'        => 'label.stock_status',
                'template'     => 'admin/list/stock_status.html.twig',
                'sortable'     => false,
                'header_style' => 'width: 10%',
                'row_align'    => 'center',
            ])
            ->add('special', null, [
                'label'        => 'label.special',
                'template'     => 'admin/list/special_bool.html.twig',
                'sortable'     => false,
                'header_style' => 'width: 5%',
                'row_align'    => 'center',
            ])
            ->add('ride', null, [
                'label'        => 'label.test_ride',
                'template'     => 'admin/list/special_bool.html.twig',
                'sortable'     => false,
                'header_style' => 'width: 5%',
                'row_align'    => 'center',
            ])
            ->add('_action', 'actions', [
                'label'   => 'label.action',
                'actions' => [
                    'move' => [
                        'template'                  => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig',
                        'enable_top_bottom_buttons' => false,
                    ],
                    'edit'    => [],
                    'clone'   => [
                        'template' => 'admin/action/clone.html.twig',
                    ],
                    'delete' => [],
                ],
                'header_style' => 'width: 18%',
                'row_align'    => 'center',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name', null, [
                'label' => 'label.name',
            ])
            ->add('lineup', null, [
                'label' => 'label.lineup',
            ])
            ->add('special', null, [
                'label' => 'label.special',
            ])
            ->add('ride', null, [
                'label' => 'label.test_ride',
            ])
            ->add(
                'stockStatus',
                'doctrine_orm_string',
                [
                'label' => 'label.stock_status',
            ],
                StockStatusType::class
            )
        ;
    }
}
