<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Lineup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lineup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lineup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lineup[]    findAll()
 * @method Lineup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineupRepository extends ServiceEntityRepository
{
    /**
     * LineupRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lineup::class);
    }

    /**
     * @return Lineup[]
     */
    public function findSorted()
    {
        $queryBuilder = $this->createQueryBuilder('lineup');

        $queryBuilder
            ->orderBy('lineup.position', Criteria::ASC)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
