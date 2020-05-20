<?php

namespace App\Repository;

use App\Entity\UserEmailAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserEmailAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEmailAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEmailAddress[]    findAll()
 * @method UserEmailAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEmailAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEmailAddress::class);
    }

    // /**
    //  * @return UserEmailAddress[] Returns an array of UserEmailAddress objects
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
    public function findOneBySomeField($value): ?UserEmailAddress
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
