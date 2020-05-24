<?php

namespace App\Repository;

use App\Entity\UserGender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGender|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGender|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGender[]    findAll()
 * @method UserGender[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGender::class);
    }

    // /**
    //  * @return UserGender[] Returns an array of UserGender objects
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
    public function findOneBySomeField($value): ?UserGender
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
