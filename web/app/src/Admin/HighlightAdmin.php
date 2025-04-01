<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\LocationType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HighlightAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', TextType::class, [
                'label' => 'label.title',
            ])
//            ->add('subtitle', TextType::class, [
//                'label'    => 'label.subtitle',
//                'required' => false,
//            ])
            ->add('description', TextareaType::class, [
                'label' => 'label.description',
            ])
            ->add('image', ModelListType::class, [
                'label'    => 'label.image',
                'required' => false,
            ], [
                'link_parameters' => [
                    'context'  => 'highlight',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->add('location', LocationType::class, [
                'label' => 'label.location',
            ])
            ->add('position', HiddenType::class, [
                'attr' => [
                    'hidden' => true,
                ],
            ])
        ;
    }
}
