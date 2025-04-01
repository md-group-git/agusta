<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\TechSection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechSectionParentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class'         => TechSection::class,
            'query_builder' => function (EntityRepository $er) {
                $query = $er->createQueryBuilder('techSection');
                $query
                    ->where($query->expr()->isNull('techSection.parent'))
                    ->orderBy('techSection.name', Criteria::ASC)
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
        return 'TechSectionParentType';
    }
}
