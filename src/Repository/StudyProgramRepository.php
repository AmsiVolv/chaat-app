<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\StudyProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method StudyProgram|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyProgram|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyProgram[]    findAll()
 * @method StudyProgram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyProgram::class);
    }

    // /**
    //  * @return StudyProgram[] Returns an array of StudyProgram objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudyProgram
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param string $externalId
     * @return StudyProgram|null
     * @throws NonUniqueResultException
     */
    public function getByExternalId(string $externalId): ?StudyProgram
    {
        $qb = $this->createQueryBuilder('sp');

        $qb->where($qb->expr()->eq('sp.externalId', ':externalId'))
            ->setParameter('externalId', $externalId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param StudyProgram $studyProgram
     * @return StudyProgram
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(StudyProgram $studyProgram): StudyProgram
    {
        $this->getEntityManager()->persist($studyProgram);
        $this->getEntityManager()->flush();

        return $studyProgram;
    }

    /**
     * @return StudyProgram[]
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('sp');

        return $qb->getQuery()->getResult();
    }
}
