<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * CreateUserCommand constructor.
     *
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Create a new user')
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            $output->writeln('User already exists');

            return;
        }

        $user = new User();
        $user
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->encoder->encodePassword($user, $password))
        ;

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success('User successfully added!');
        } catch (ORMException $e) {
            $io->error($e->getMessage());
        }
    }
}
