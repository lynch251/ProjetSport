<?php

namespace App\Repository;

use App\Entity\RoleUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RoleUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoleUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoleUtilisateur[]    findAll()
 * @method RoleUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoleUtilisateur::class);
    }

//    public function findOneBy($titre_symfony): ?RoleUtilisateur {
//
//
//    }

    // /**
    //  * @return RoleUtilisateur[] Returns an array of RoleUtilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoleUtilisateur
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
