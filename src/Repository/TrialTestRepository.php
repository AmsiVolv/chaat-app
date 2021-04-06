<?php

namespace App\Repository;

use App\Entity\TrialTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TrialTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrialTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrialTest[]    findAll()
 * @method TrialTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrialTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrialTest::class);
    }

    // /**
    //  * @return TrialTest[] Returns an array of TrialTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrialTest
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getAllForResponse(): ?array
    {
        $qb = $this->createQueryBuilder('tt');

        $qb->select('tt.id, tt.trialTestTitle, tt.trialTestLink, tt.keyword');

        return $qb->getQuery()->getResult();
    }

    public function getByLink(string $link): ?array
    {
        $qb = $this->createQueryBuilder('tt');

        $qb->where($qb->expr()->eq('tt.trialTestLink', ':link'))
            ->setParameter('link', $link);

        return $qb->getQuery()->getResult();
    }

    public function store(TrialTest $trialTest): TrialTest
    {
        $this->getEntityManager()->persist($trialTest);
        $this->getEntityManager()->flush();

        return $trialTest;
    }
}
