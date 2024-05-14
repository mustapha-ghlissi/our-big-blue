<?php

namespace App\Repository;

use App\Entity\CapturedData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CapturedData>
 *
 * @method CapturedData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CapturedData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CapturedData[]    findAll()
 * @method CapturedData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CapturedDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapturedData::class);
    }

    public function getCount()
    {
        return $this->createQueryBuilder('cd')
            ->select('count(cd.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
