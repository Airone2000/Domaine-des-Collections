<?php

namespace App\Repository;

use App\Entity\FormComponentSize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormComponentSize|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormComponentSize|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormComponentSize[]    findAll()
 * @method FormComponentSize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormComponentSizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormComponentSize::class);
    }

    // /**
    //  * @return FormComponentSize[] Returns an array of FormComponentSize objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormComponentSize
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
