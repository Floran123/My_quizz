<?php

namespace App\Repository;

use App\Entity\AdminEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminEmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminEmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminEmail[]    findAll()
 * @method AdminEmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminEmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminEmail::class);
    }

    // /**
    //  * @return AdminEmail[] Returns an array of AdminEmail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminEmail
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
