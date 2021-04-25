<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\CourseSheduling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
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

    public function getAllByCourseId(string $courseId): array
    {
        $qb = $this->createQueryBuilder('cs');
        $selectString = $this->getSelectQueryForCourse();

        $qb->select($selectString)
            ->innerJoin('cs.course', 'c')
            ->where($qb->expr()->eq('c.id', ':courseId'))
            ->setParameter('courseId', $courseId);

        return $qb->getQuery()->getResult();
    }

    private function getSelectQueryForCourse(): string
    {
        $selectString = '';
        $courseSchedulingKeys = CourseSheduling::getPrimaryKeys();

        foreach ($courseSchedulingKeys as $courseSchedulingKey) {
            $selectString .= sprintf(' %s.%s ', CourseRepository::COURSE_SCHEDULING_ALIAS, $courseSchedulingKey);
        }

        return str_replace('  ', ', ', trim($selectString));
    }

    public function getWithString(string $prepareSelectString, stdClass $request): array
    {
        $qb = $this->createQueryBuilder('cs');

        $qb->select($prepareSelectString)
            ->innerJoin('cs.course', 'c')
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
