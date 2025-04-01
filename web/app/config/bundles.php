<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class                => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                          => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class                    => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class                 => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class     => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class                  => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class            => ['dev' => true, 'test' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                    => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class                        => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class                        => ['dev' => true],
    Sonata\CoreBundle\SonataCoreBundle::class                            => ['all' => true],
    Sonata\EasyExtendsBundle\SonataEasyExtendsBundle::class              => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class                      => ['all' => true],
    Sonata\MediaBundle\SonataMediaBundle::class                          => ['all' => true],
    Sonata\BlockBundle\SonataBlockBundle::class                          => ['all' => true],
    Knp\Bundle\MenuBundle\KnpMenuBundle::class                           => ['all' => true],
    Sonata\AdminBundle\SonataAdminBundle::class                          => ['all' => true],
    SilasJoisten\Sonata\MultiUploadBundle\SonataMultiUploadBundle::class => ['all' => true],
    Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle::class    => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class    => ['all' => true],
    Pix\SortableBehaviorBundle\PixSortableBehaviorBundle::class          => ['all' => true],
    Knp\DoctrineBehaviors\DoctrineBehaviorsBundle::class                 => ['all' => true],
    Knp\Bundle\MarkdownBundle\KnpMarkdownBundle::class                   => ['all' => true],
    FOS\CKEditorBundle\FOSCKEditorBundle::class                          => ['all' => true],
    Sonata\FormatterBundle\SonataFormatterBundle::class                  => ['all' => true],
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class               => ['all' => true],
];
