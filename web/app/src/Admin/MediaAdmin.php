<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\MediaBundle\Admin\BaseMediaAdmin;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MediaAdmin extends BaseMediaAdmin
{
    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection->add('create_gallery', 'create/gallery/uploaded/medias');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $showFilter = true !== $this->getPersistentParameter('hide_context');

        $type = ChoiceType::class;
        $options = $this->getOptions();

        $datagridMapper
            ->add('name')
            ->add('providerReference')
            ->add('enabled')
            ->add('context', null, ['show_filter' => $showFilter], $type, $options);

        if (null !== $this->categoryManager) {
            $datagridMapper->add('category', null, ['show_filter' => false]);
        }

        $datagridMapper
            ->add('width')
            ->add('height')
            ->add('contentType')
        ;

        $datagridMapper->add('providerName', ChoiceFilter::class, [
            'field_options' => [
                'choices'  => $this->getProviders(),
                'required' => false,
                'multiple' => false,
                'expanded' => false,
            ],
            'field_type' => ChoiceType::class,
        ]);
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [
            'choices' => [],
        ];

        foreach ($this->pool->getContexts() as $name => $context) {
            $options['choices'][$name] = $name;
        }

        return $options;
    }

    /**
     * @return array
     */
    private function getProviders(): array
    {
        $providers = [];

        $context = $this->pool->getDefaultContext();
        $providerNames = (array) $this->pool->getProviderNamesByContext($this->getPersistentParameter($context));

        foreach ($providerNames as $name) {
            $providers[$name] = $name;
        }

        return $providers;
    }
}
