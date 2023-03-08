<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    public function save(Produits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Produits[] Returns an array of Produits objects
//     */
   public function findByPrix():array
   {
       return $this->createQueryBuilder('p')
           
           ->orderBy('p.prix', 'DESC')
           ->getQuery()
           ->getResult()
       ;
   }
   public function findByLibelle($search)
    {
        return $this->createQueryBuilder('res')
                    ->andWhere('res.libelle LIKE :rech OR res.description LIKE :rech')
                    ->setParameter('rech','%'.$search.'%')
                    ->getQuery()
                    ->getResult();
    }

    public function findByprixintervalle($value1,$value2): array
    {
        return $this->createQueryBuilder('res')
                    ->andWhere('res.prix > :value1 ')
                    ->andWhere('res.prix < :value2')
                    ->setParameter('value1',$value1)
                    ->setParameter('value2',$value2)
                    ->getQuery()
                    ->getResult();
    }


    function  get_prod_with_moy()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql='


select produits.id, produits.libelle,produits.description,produits.prix,categories.nom,produits.image,produits.likes,produits.dislikes, ROUND(AVG(review.note),1) as e from review RIGHT join produits on (review.produit_id=produits.id) inner join categories on (produits.type_id=categories.id) group by (produits.id);
 
  ';
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

    }



    function  show_comment($str)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql='
    select commentaire from review where review.produit_id = '.$str.' ; 
    ';
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

    }


//    public function findOneBySomeField($value): ?Produits
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
