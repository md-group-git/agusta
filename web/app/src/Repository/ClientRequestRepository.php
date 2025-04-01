<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ClientRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientRequest[]    findAll()
 * @method ClientRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRequestRepository extends ServiceEntityRepository
{
    /**
     * ClientRequestRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientRequest::class);
    }
}
