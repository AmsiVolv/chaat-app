<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Faculty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Faculty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faculty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faculty[]    findAll()
 * @method Faculty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacultyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faculty::class);
    }

    /**
     * @param Faculty $faculty
     * @return Faculty
     * @throws \Throwable
     */
    public function store(Faculty $faculty): Faculty
    {
        $this->getEntityManager()->persist($faculty);
        $this->getEntityManager()->flush($faculty);

        return $faculty;
    }

    /**
     * @return Faculty[]
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('f');

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Faculty[] Returns an array of Faculty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Faculty
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
