<?php

namespace App\Repository;

use App\Entity\UserBirthdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBirthdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBirthdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBirthdate[]    findAll()
 * @method UserBirthdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBirthdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBirthdate::class);
    }

    // /**
    //  * @return UserBirthdate[] Returns an array of UserBirthdate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserBirthdate
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
