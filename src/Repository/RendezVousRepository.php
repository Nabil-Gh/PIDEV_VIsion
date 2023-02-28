<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
        
    }

    public function save(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RendezVous[] Returns an array of RendezVous objects
//     */
    public function findByHour($value):array
    {
        return $this->createQueryBuilder('r')
        ->andWhere('r.med = :val')
        ->setParameter('val', $value)
        ->orderBy('r.date_rv', 'ASC')
        ->addOrderBy('r.heure_rv', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findByuser($value) 
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.med = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    


//    public function findOneBySomeField($value): ?RendezVous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }







public function findByMedecinOrPatient($search)
    {
        return $this->createQueryBuilder('rv')
                    ->innerJoin(User::class, 'med', 'WITH', 'rv.med = med.id')
                    ->innerJoin(User::class, 'patient', 'WITH', 'rv.patient = patient.id')
                    ->where('med.nom LIKE :nom OR med.prenom LIKE :nom ')
                    ->orWhere('patient.nom LIKE :nom OR patient.prenom LIKE :nom ')
                    ->setParameter('nom','%'.$search.'%')
                    ->getQuery()
                    ->getResult();
    }


}
