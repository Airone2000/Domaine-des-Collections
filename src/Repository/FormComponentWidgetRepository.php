<?php

namespace App\Repository;

use App\Entity\FormComponentWidget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormComponentWidget|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormComponentWidget|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormComponentWidget[]    findAll()
 * @method FormComponentWidget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormComponentWidgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormComponentWidget::class);
    }

    // /**
    //  * @return FormComponentWidget[] Returns an array of FormComponentWidget objects
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
    public function findOneBySomeField($value): ?FormComponentWidget
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
