<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConsultingHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsultingHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultingHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultingHours[]    findAll()
 * @method ConsultingHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultingHoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultingHours::class);
    }

    // /**
    //  * @return ConsultingHours[] Returns an array of ConsultingHours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConsultingHours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
