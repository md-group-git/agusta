<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PaintColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaintColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintColor[]    findAll()
 * @method PaintColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintColorRepository extends ServiceEntityRepository
{
    /**
     * PaintColorRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaintColor::class);
    }
}
