<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\StudyProgramLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method StudyProgramLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyProgramLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyProgramLanguage[]    findAll()
 * @method StudyProgramLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyProgramLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyProgramLanguage::class);
    }

    // /**
    //  * @return StudyProgramLanguage[] Returns an array of StudyProgramLanguage objects
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
    public function findOneBySomeField($value): ?StudyProgramLanguage
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
     * @param string $language
     * @return StudyProgramLanguage|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByLanguage(string $language): ?StudyProgramLanguage
    {
        $qb = $this->createQueryBuilder('l');

        $qb->where($qb->expr()->eq('l.language', ':language'))
            ->setParameter(':language', $language);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param StudyProgramLanguage $studyInformationLanguage
     * @return StudyProgramLanguage
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(StudyProgramLanguage $studyInformationLanguage): StudyProgramLanguage
    {
        $this->getEntityManager()->persist($studyInformationLanguage);
        $this->getEntityManager()->flush();

        return $studyInformationLanguage;
    }

    /**
     * @return StudyProgramLanguage[]
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('l');

        return $qb->getQuery()->getResult();
    }
}
