<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Model;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugUpdateModelPositionCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DebugUpdateModelPositionCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:debug:update-model-position');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $models = $this->entityManager->getRepository(Model::class)->findAll();

        $position = 1;
        foreach ($models as $model) {
            $model->setPosition($position++);
        }

        try {
            $this->entityManager->flush();

            $io->success('Model positions successfully updated!');
        } catch (ORMException $e) {
            $io->error($e->getMessage());
        }
    }
}
