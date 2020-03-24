<?php

namespace App\Repository;

use App\Entity\Seance;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use PDO;

/**
 * @method Seance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seance[]    findAll()
 * @method Seance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    public function findByUser(Utilisateur $utilisateur) {


        try {
            $id = $utilisateur->getId();
            $requete = $this->getEntityManager()->getConnection()->prepare('SELECT * FROM seance WHERE utilisateur_id = :utilisateur_id');
            $requete->bindValue(':utilisateur_id', (int) $id, \PDO::PARAM_INT);
            $requete->execute();
            return $requete->fetchAll();

        } catch (DBALException $e) {
            echo($e->getMessage());
        }
    }

    public function findNumberByUser(Utilisateur $utilisateur) {

        try{
            $id = $utilisateur->getId();
            $requete = $this->getEntityManager()->getConnection()->prepare('SELECT COUNT(*) as nb FROM seance WHERE utilisateur_id = :utilisateur_id');
            $requete->bindValue(':utilisateur_id', (int) $id, \PDO::PARAM_INT);
            $requete->execute();
            $seance = $requete->fetch();
            return $seance['nb'];

        } catch (DBALException $e) {
            echo($e->getMessage());
        }
    }
    // /**
    //  * @return Seance[] Returns an array of Seance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Seance
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
