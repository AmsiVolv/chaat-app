<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\StudyProgramAim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method StudyProgramAim|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyProgramAim|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyProgramAim[]    findAll()
 * @method StudyProgramAim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyProgramAimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyProgramAim::class);
    }

    // /**
    //  * @return StudyProgramAim[] Returns an array of StudyProgramAim objects
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
    public function findOneBySomeField($value): ?StudyProgramAim
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
     * @param string $programAim
     * @return StudyProgramAim|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByName(string $programAim): ?StudyProgramAim
    {
        $qb = $this->createQueryBuilder('pa');

        $qb->where($qb->expr()->eq('pa.aim', ':aim'))
            ->setParameter(':aim', $programAim);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param StudyProgramAim $programAim
     * @return StudyProgramAim
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(StudyProgramAim $programAim): StudyProgramAim
    {
        $this->getEntityManager()->persist($programAim);
        $this->getEntityManager()->flush();

        return $programAim;
    }

    /**
     * @return StudyProgramAim[]
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('pa');

        return $qb->getQuery()->getResult();
    }

    public function getByStudyProgramId(int $studyProgramId): array
    {
        $qb = $this->createQueryBuilder('pa');

        $qb->select('pa.id, pa.aim')
            ->join('pa.studyPrograms', 'sp')
            ->where($qb->expr()->eq('sp.id', ':studyProgramId'))
            ->setParameter('studyProgramId', $studyProgramId);

        return $qb->getQuery()->getResult();
    }
}
