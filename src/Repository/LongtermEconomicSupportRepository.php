<?php

namespace App\Repository;

use App\Entity\LongtermEconomicSupport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LongtermEconomicSupport>
 *
 * @method LongtermEconomicSupport|null find($id, $lockMode = null, $lockVersion = null)
 * @method LongtermEconomicSupport|null findOneBy(array $criteria, array $orderBy = null)
 * @method LongtermEconomicSupport[]    findAll()
 * @method LongtermEconomicSupport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LongtermEconomicSupportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LongtermEconomicSupport::class);
    }
}
