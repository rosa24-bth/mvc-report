<?php

namespace App\Repository;

use App\Entity\LowEconomicStandard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LowEconomicStandard>
 */
class LowEconomicStandardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LowEconomicStandard::class);
    }
}
