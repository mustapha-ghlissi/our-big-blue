<?php

namespace App\Repository;

use App\Entity\CapturedImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CapturedImage>
 *
 * @method CapturedImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CapturedImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CapturedImage[]    findAll()
 * @method CapturedImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CapturedImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapturedImage::class);
    }

//    /**
//     * @return CapturedImage[] Returns an array of CapturedImage objects
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

//    public function findOneBySomeField($value): ?CapturedImage
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
