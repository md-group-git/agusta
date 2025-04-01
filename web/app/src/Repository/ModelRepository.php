<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Model;
use App\Enum\StockStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Model|null find($id, $lockMode = null, $lockVersion = null)
 * @method Model|null findOneBy(array $criteria, array $orderBy = null)
 * @method Model[]    findAll()
 * @method Model[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelRepository extends ServiceEntityRepository
{
    /**
     * ModelRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

    /**
     * @param string $lineup
     * @param string $model
     *
     * @return Model
     */
    public function findOneByLineupAndModel(string $lineup, string $model): ?Model
    {
        $queryBuilder = $this->createQueryBuilder('model');

        $queryBuilder
            ->join('model.lineup', 'lineup')
            ->where($queryBuilder->expr()->eq('lineup.slug', ':lineup'))
            ->andWhere($queryBuilder->expr()->eq('model.slug', ':model'))
            ->setParameters([
                'lineup' => $lineup,
                'model'  => $model,
            ])
        ;

        try {
            return $queryBuilder->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param Model|null $excluded
     *
     * @return Model[]
     */
    public function findInStock(Model $excluded = null)
    {
        $queryBuilder = $this->createQueryBuilder('model');
        $queryBuilder
            ->join('model.lineup', 'lineup')
            ->where($queryBuilder->expr()->eq('model.stockStatus', ':stockStatus'))
            ->addOrderBy('model.position', Criteria::ASC)
            ->setParameter('stockStatus', StockStatusEnum::IN_STOCK)
        ;

        if ($excluded) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->notIn('model', ':excluded'))
                ->setParameter('excluded', $excluded)
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return Model[]
     */
    public function findForRide()
    {
        $queryBuilder = $this->createQueryBuilder('model');

        $queryBuilder
            ->join('model.lineup', 'lineup')
            ->where($queryBuilder->expr()->eq('model.ride', ':ride'))
            ->addOrderBy('model.position', Criteria::ASC)
            ->setParameter('ride', true)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function getLastPosition(): int
    {
        try {
            $queryBuilder = $this->createQueryBuilder('model');

            return (int) $queryBuilder
                ->select('MAX(model.position)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
