<?php

namespace App\Repository;

use App\Entity\Reponse;
use App\Entity\Reclamation;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reponse>
 *
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    public function save(Reponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
 //   * @return Reponse[] Returns an array of Reponse objects
  //  */
   public function findByreclamation($value): array
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.idRec = :val')
           ->setParameter('val', $value)
           
           ->getQuery()
           ->getResult()
       ;
   }

   function  reprec($str)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql='


    select message , date_creation , objet from reclamation join reponse on reponse.id_rec_id = reclamation.id where id_rec_id = '.$str.' ; 
    ';
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    
    

//    public function findOneBySomeField($value): ?Reponse
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
