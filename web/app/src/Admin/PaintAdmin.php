<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\PaintColorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PaintAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('paintColor', PaintColorType::class, [
                'label'    => 'label.color',
                'required' => true,
            ])
            ->add('gallery', ModelListType::class, [
                'label'    => 'label.gallery360',
                'required' => false,
            ], [
                'link_parameters' => [
                    'context'  => 'circular',
                ],
            ])
            ->add('note', TextType::class, [
                'label'    => 'label.note',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label'    => 'label.enabled',
                'required' => false,
            ])
            ->add('position', HiddenType::class, [
                'attr' => [
                    'hidden' => true,
                ],
            ])
        ;
    }
}
