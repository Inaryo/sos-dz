<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserSearch;
use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param UserSearch $search
     * @return Query
     */
    public function findCompaniesBySearch(UserSearch $search): Query
    {

        $query = $this->createQueryBuilder('e')
            ->orderBy('e.username','ASC');

        if ($search->getCategory()) {

            $category = $search->getCategory();
            $query->andWhere('e.category = :category')
                ->setParameter('category',$category);
        }

        if ($search->getZone()) {
            $zone = $search->getZone();
            $query->andWhere('e.zone = :zone')
                ->setParameter('zone',$zone);
        }

        return $query->getQuery();

    }

    /**
     * @param int $zone
     * @return User[]
     */
    public function findCompaniesByZone(Zone $zone): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.zone = :val')
            ->setParameter('val', $zone)
            ->andWhere('u.activated = true')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
