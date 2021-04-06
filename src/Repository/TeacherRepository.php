<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\CourseSheduling;
use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    // /**
    //  * @return Teacher[] Returns an array of Teacher objects
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
    public function findOneBySomeField($value): ?Teacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Teacher $teacher
     * @return Teacher
     * @throws Throwable
     */
    public function store(Teacher $teacher): Teacher
    {
        $this->getEntityManager()->persist($teacher);
        $this->getEntityManager()->flush($teacher);

        return $teacher;
    }

    /**
     * @param string|null $uri
     * @return Teacher|null
     * @throws NonUniqueResultException
     */
    public function getByUri(?string $uri): ?Teacher
    {
        $qb = $this->createQueryBuilder('t');

        $qb->where($qb->expr()->eq('t.teacherUri', ':uri'))
            ->setParameter('uri', $uri);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Teacher[]
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('t');

        return $qb->getQuery()->getResult();
    }


    public function getAllByCourseId(string $courseId): array
    {
        $qb = $this->createQueryBuilder('t');
        $selectString = $this->getSelectQueryForCourse();

        $qb->select($selectString)
            ->innerJoin('t.courses', 'c')
            ->where($qb->expr()->eq('c.id', ':courseId'))
            ->setParameter('courseId', $courseId);

        return $qb->getQuery()->getResult();
    }

    private function getSelectQueryForCourse(): string
    {
        $selectString = '';
        $courseSchedulingKeys = Teacher::getPrimaryKeys();

        foreach ($courseSchedulingKeys as $courseSchedulingKey) {
            $selectString .= sprintf(' %s.%s ', CourseRepository::TEACHER_ALIAS, $courseSchedulingKey);
        }

        return str_replace('  ', ', ', trim($selectString));
    }
}
