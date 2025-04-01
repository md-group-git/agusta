<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Lineup;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LineupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class'         => Lineup::class,
            'query_builder' => function (EntityRepository $er) {
                $query = $er->createQueryBuilder('lineup');
                $query
                    ->orderBy('lineup.position', Criteria::ASC)
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
        return 'LineupType';
    }
}
