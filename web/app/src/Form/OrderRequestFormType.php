<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Model;
use App\Entity\OrderRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderRequestFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', EntityType::class, [
                'class' => Model::class,
            ])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => OrderRequest::class,
            'csrf_protection'   => false,
        ]);
    }
}
