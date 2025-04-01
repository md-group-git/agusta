<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Model;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class'         => Model::class,
            'query_builder' => function (EntityRepository $er) {
                $query = $er->createQueryBuilder('model');
                $query
                    ->join('model.lineup', 'lineup')
                    ->addOrderBy('lineup.name', Criteria::ASC)
                    ->addOrderBy('CAST(model.name AS UNSIGNED)', Criteria::ASC)
                    ->addOrderBy('model.name', Criteria::ASC)
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
        return 'ModelType';
    }
}
