<?php

namespace App\Repository;

use App\Entity\PropertyNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyNote[]    findAll()
 * @method PropertyNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyNote::class);
    }

    // /**
    //  * @return PropertyNote[] Returns an array of PropertyNote objects
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
    public function findOneBySomeField($value): ?PropertyNote
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
