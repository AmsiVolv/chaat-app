<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\PreparatoryCourses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PreparatoryCourses|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreparatoryCourses|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreparatoryCourses[]    findAll()
 * @method PreparatoryCourses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparatoryCoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreparatoryCourses::class);
    }

    // /**
    //  * @return PreparatoryCourses[] Returns an array of PreparatoryCourses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PreparatoryCourses
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return array
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('pc');

        return $qb->getQuery()->getResult();
    }
}
