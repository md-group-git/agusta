<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Lineup;
use App\Repository\LineupRepository;

class LineupListService
{
    /**
     * @var LineupRepository
     */
    private $repository;

    /**
     * LineupListService constructor.
     *
     * @param LineupRepository $repository
     */
    public function __construct(LineupRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Lineup[]
     */
    public function getSorted()
    {
        return $this->repository->findSorted();
    }
}
