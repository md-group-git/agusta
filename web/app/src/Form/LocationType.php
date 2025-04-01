<?php

declare(strict_types=1);

namespace App\Form;

use App\Enum\LocationEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choices', array_flip(LocationEnum::choices()));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'LocationType';
    }
}
