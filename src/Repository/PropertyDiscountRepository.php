<?php

namespace App\Repository;

use App\Entity\PropertyDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyDiscount[]    findAll()
 * @method PropertyDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyDiscount::class);
    }

    // /**
    //  * @return PropertyDiscount[] Returns an array of PropertyDiscount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PropertyDiscount
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
