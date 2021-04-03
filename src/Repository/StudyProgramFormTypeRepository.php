<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\StudyProgramFormType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method StudyProgramFormType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyProgramFormType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyProgramFormType[]    findAll()
 * @method StudyProgramFormType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyProgramFormTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyProgramFormType::class);
    }

    // /**
    //  * @return StudyProgramFormType[] Returns an array of StudyProgramFormType objects
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
    public function findOneBySomeField($value): ?StudyProgramFormType
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
     * @param string $formType
     * @return StudyProgramFormType|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByName(string $formType): ?StudyProgramFormType
    {
        $qb = $this->createQueryBuilder('sf');

        $qb->where($qb->expr()->eq('sf.form', ':form'))
            ->setParameter(':form', $formType);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param StudyProgramFormType $studyInformationFormType
     * @return StudyProgramFormType
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(StudyProgramFormType $studyInformationFormType): StudyProgramFormType
    {
        $this->getEntityManager()->persist($studyInformationFormType);
        $this->getEntityManager()->flush();

        return $studyInformationFormType;
    }
}
