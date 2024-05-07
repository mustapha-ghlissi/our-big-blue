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

//    /**
//     * @return CapturedData[] Returns an array of CapturedData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CapturedData
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
