<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Reading|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reading|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reading[]    findAll()
 * @method Reading[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reading::class);
    }

    // /**
    //  * @return Reading[] Returns an array of Reading objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reading
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Reading $reading
     * @return Reading
     * @throws Throwable
     */
    public function store(Reading $reading): Reading
    {
        $this->getEntityManager()->persist($reading);
        $this->getEntityManager()->flush($reading);

        return $reading;
    }

    /**
     * @param string|null $ISBN
     * @return Reading|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByIsbn(?string $ISBN): ?Reading
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where($qb->expr()->eq('r.ISBN', ':ISBN'))
            ->setParameter('ISBN', $ISBN);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getAllByCourseId(string $courseId): array
    {
        $qb = $this->createQueryBuilder('r');
        $selectString = $this->getSelectQueryForCourse();

        $qb->select($selectString)
            ->innerJoin('r.course', 'c')
            ->where($qb->expr()->eq('c.id', ':courseId'))
            ->setParameter('courseId', $courseId);

        return $qb->getQuery()->getResult();
    }

    private function getSelectQueryForCourse(): string
    {
        $selectString = '';
        $readingKeys = Reading::getPrimaryKeys();

        foreach ($readingKeys as $readingKey) {
            $selectString .= sprintf(' %s.%s ', CourseRepository::READING_ALIAS, $readingKey);
        }

        return str_replace('  ', ', ', trim($selectString));
    }

    public function getWithString(string $prepareSelectString, stdClass $request): array
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($prepareSelectString)
            ->innerJoin('r.course', 'c')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('c.subjectCode', ':subjectCode'),
                    $qb->expr()->eq('c.courseTitle', ':courseTitle'),
                )
            )
            ->setParameter('subjectCode', $request->course)
            ->setParameter('courseTitle', $request->course);

        return $qb->getQuery()->getResult();
    }
}
