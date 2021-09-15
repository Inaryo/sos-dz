<?php

namespace App\Repository;

use App\Entity\Catastrophe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catastrophe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catastrophe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catastrophe[]    findAll()
 * @method Catastrophe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatastropheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catastrophe::class);
    }

    // /**
    //  * @return Catastrophe[] Returns an array of Catastrophe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Catastrophe
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
