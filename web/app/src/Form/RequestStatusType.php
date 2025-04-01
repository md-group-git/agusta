<?php

declare(strict_types=1);

namespace App\Form;

use App\Enum\RequestStatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestStatusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choices', array_flip(RequestStatusEnum::choices()));
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
        return 'RequestStatusType';
    }
}
