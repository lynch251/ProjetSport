<?php

namespace App\Repository;

use App\Entity\Seance;
use App\Entity\Utilisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Utilisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisation[]    findAll()
 * @method Utilisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisation::class);
    }
    public function findBySeance(Seance $seance) {
        try {
            $id = $seance->getId();
            $requete = $this->getEntityManager()->getConnection()->prepare('SELECT * FROM utilisation WHERE seance_id = :seance_id');
            $requete->bindValue(':seance_id', (int) $id, \PDO::PARAM_INT);
            $requete->execute();
            return $requete->fetchAll();

        } catch (DBALException $e) {
            echo($e->getMessage());
        }
    }
    // /**
    //  * @return Utilisation[] Returns an array of Utilisation objects
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
    public function findOneBySomeField($value): ?Utilisation
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
