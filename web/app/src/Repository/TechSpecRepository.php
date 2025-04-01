<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TechSpec;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TechSpec|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechSpec|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechSpec[]    findAll()
 * @method TechSpec[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechSpecRepository extends ServiceEntityRepository
{
    /**
     * TechSpecRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechSpec::class);
    }
}
