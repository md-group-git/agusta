<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\PaintColor;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaintColorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class'         => PaintColor::class,
            'query_builder' => function (EntityRepository $er) {
                $query = $er->createQueryBuilder('paintColor');
                $query
                    ->orderBy('paintColor.name', Criteria::ASC)
                ;

                return $query;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Bridge\Doctrine\Form\Type\EntityType';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'PaintColorType';
    }
}
