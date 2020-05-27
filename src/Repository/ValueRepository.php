<?php

namespace App\Repository;

use App\Entity\FormComponent;
use App\Entity\Thing;
use App\Entity\Value;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Value|null find($id, $lockMode = null, $lockVersion = null)
 * @method Value|null findOneBy(array $criteria, array $orderBy = null)
 * @method Value[]    findAll()
 * @method Value[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Value::class);
    }

    public function deleteByThingAndFormComponent(Thing $thing, FormComponent $formComponent): void
    {
        $qb = $this->createQueryBuilder('v');
        $qb
            ->delete()
            ->where('v.thing = :thing')
            ->andWhere('v.formComponent = :formComponent')
            ->setParameter('thing', $thing)
            ->setParameter('formComponent', $formComponent)
            ->getQuery()
            ->execute()
        ;
    }
}
