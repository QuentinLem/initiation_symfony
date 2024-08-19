<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    //    /**
    //     * @return Personne[] Returns an array of Personne objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    /**
     * 
     */
    public function findPersonnesByAgeInterval($min, $max): array
    {
        $qb =  $this->createQueryBuilder('p');
        $this->qbAddAgeInterval($qb, $min, $max);
        return $qb->getQuery()
                ->getResult()
        ;
    }

    public function getStatsOnPersonnesByAgeInterval($min, $max): array
    {
        $qb =  $this->createQueryBuilder('p')
                    ->select('avg(p.age) as ageMoyen, count(p.id) as nbPersonnes');
        $this->qbAddAgeInterval($qb, $min, $max);
        return $qb->getQuery()
                ->getScalarResult()
        ;
    }

    private function qbAddAgeInterval(QueryBuilder $qb, $min, $max){
//      /!\ setParameterS = bug
//      $parameters = new ArrayCollection(['ageMin' => $min, 'ageMax' => $max]);
        $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
        ->setParameter('ageMin', $min)
        ->setParameter('ageMax', $max);
//      ->setParameters($parameters);
    }

    //    public function findOneBySomeField($value): ?Personne
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
