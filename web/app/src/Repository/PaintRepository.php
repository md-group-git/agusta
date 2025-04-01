<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Paint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Paint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paint[]    findAll()
 * @method Paint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintRepository extends ServiceEntityRepository
{
    /**
     * PaintRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paint::class);
    }
}
