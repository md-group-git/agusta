<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ServiceRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceRequest[]    findAll()
 * @method ServiceRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRequestRepository extends ServiceEntityRepository
{
    /**
     * ServiceRequestRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceRequest::class);
    }
}
