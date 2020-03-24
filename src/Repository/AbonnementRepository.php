<?php

namespace App\Repository;

use App\Entity\Abonnement;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Machine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Machine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Machine[]    findAll()
 * @method Machine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    /** @method Abonnement findNumberByUser(Utilisateur $utilisateur) */
    public function findNumberByUser(Utilisateur $utilisateur) {

        try{
            $id = $utilisateur->getId();
            $requete = $this->getEntityManager()->getConnection()->prepare('SELECT COUNT(*) as nb FROM abonnement WHERE client_id = :client_id');
            $requete->bindValue(':client_id', (int) $id, \PDO::PARAM_INT);
            $requete->execute();
            $seance = $requete->fetch();
            return $seance['nb'];

        } catch (DBALException $e) {
            echo($e->getMessage());
        }
    }

    // /**
    //  * @return Machine[] Returns an array of Machine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Machine
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
