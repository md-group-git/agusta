<?php

declare(strict_types=1);

namespace App\Form;

use App\Enum\StockStatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockStatusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choices', array_flip(StockStatusEnum::choices()));
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
        return 'StockStatusType';
    }
}
