<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TechSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TechSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechSection[]    findAll()
 * @method TechSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechSectionRepository extends ServiceEntityRepository
{
    /**
     * TechSectionRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechSection::class);
    }
}
