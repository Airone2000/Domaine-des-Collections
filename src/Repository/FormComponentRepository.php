<?php

namespace App\Repository;

use App\Entity\FormComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormComponent[]    findAll()
 * @method FormComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormComponentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormComponent::class);
    }

    // /**
    //  * @return FormComponent[] Returns an array of FormComponent objects
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
    public function findOneBySomeField($value): ?FormComponent
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
