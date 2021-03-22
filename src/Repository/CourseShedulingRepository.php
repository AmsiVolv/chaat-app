<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\CourseSheduling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @method CourseSheduling|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseSheduling|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseSheduling[]    findAll()
 * @method CourseSheduling[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseShedulingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseSheduling::class);
    }

    // /**
    //  * @return CourseSheduling[] Returns an array of CourseSheduling objects
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
    public function findOneBySomeField($value): ?CourseSheduling
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param CourseSheduling $scheduling
     * @return CourseSheduling
     * @throws Throwable
     */
    public function store(CourseSheduling $scheduling): CourseSheduling
    {
        $this->getEntityManager()->persist($scheduling);
        $this->getEntityManager()->flush($scheduling);

        return $scheduling;
    }
}