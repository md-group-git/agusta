<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\TechSectionChildType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TechSpecAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('techSection', TechSectionChildType::class, [
                'label'    => 'label.tech_section',
                'required' => true,
            ])
            ->add('text', TextareaType::class, [
                'label' => 'label.text',
                'attr'  => ['style' => 'width: 800px'],
            ])
            ->add('position', HiddenType::class, [
                'attr' => [
                    'hidden' => true,
                ],
            ])
        ;
    }
}
