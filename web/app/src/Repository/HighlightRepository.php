<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Highlight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Highlight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Highlight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Highlight[]    findAll()
 * @method Highlight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HighlightRepository extends ServiceEntityRepository
{
    /**
     * HighlightRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Highlight::class);
    }
}
