<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RideRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RideRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method RideRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method RideRequest[]    findAll()
 * @method RideRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RideRequestRepository extends ServiceEntityRepository
{
    /**
     * RideRequestRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RideRequest::class);
    }
}
