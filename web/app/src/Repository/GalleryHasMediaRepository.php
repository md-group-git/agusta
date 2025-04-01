<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GalleryHasMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GalleryHasMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method GalleryHasMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method GalleryHasMedia[]    findAll()
 * @method GalleryHasMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryHasMediaRepository extends ServiceEntityRepository
{
    /**
     * GalleryHasMediaRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GalleryHasMedia::class);
    }
}
