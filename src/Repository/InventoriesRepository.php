<?php

namespace App\Repository;

use App\Entity\Inventories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inventories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inventories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inventories[]    findAll()
 * @method Inventories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventories::class);
    }

    // /**
    //  * @return Inventories[] Returns an array of Inventories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inventories
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
