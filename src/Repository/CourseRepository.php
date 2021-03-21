<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Course;
use App\Entity\CourseSheduling;
use App\Entity\Reading;
use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Throwable;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public const COURSE = 'course';
    public const TEACHER = 'teacher';
    public const COURSE_SCHEDULING = 'courseScheduling';
    public const READING = 'reading';

    private const COURSE_ALIAS = 'c';
    private const TEACHER_ALIAS = 't';
    private const COURSE_SCHEDULING_ALIAS = 'cs';
    private const READING_ALIAS = 'r';

    private const PROPERTY_ARRAY = [
        self::COURSE => self::COURSE_ALIAS,
        self::TEACHER => self::TEACHER_ALIAS,
        self::COURSE_SCHEDULING => self::COURSE_SCHEDULING_ALIAS,
        self::READING => self::READING_ALIAS,
    ];

    private const OBJECT_PROPERTY_ARRAY = [
        self::COURSE => Course::class,
        self::TEACHER => Teacher::class,
        self::COURSE_SCHEDULING => CourseSheduling::class,
        self::READING => Reading::class,
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    // /**
    //  * @return Subject[] Returns an array of Subject objects
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
    public function findOneBySomeField($value): ?Subject
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
     * @param Course $course
     * @return Course
     * @throws Throwable
     */
    public function store(Course $course): Course
    {
        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush($course);

        return $course;
    }

    /**
     * @return Course[]
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $course
     * @return array
     * @throws Throwable
     */
    public function getCourseInfo(string $course): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->leftJoin('c.readings', 'r')
            ->leftJoin('c.courseScheduling', 'cs')
            ->leftJoin('c.teacher', 't')
            ->addSelect('r')
            ->addSelect('cs')
            ->addSelect('t')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('c.subjectCode', ':subjectCode'),
                    $qb->expr()->eq('c.courseTitle', ':courseTitle'),
                )
            )
            ->setParameter('subjectCode', $course)
            ->setParameter('courseTitle', $course);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param stdClass $request
     * @return Course|null
     * @throws Throwable
     */
    public function getCourseInfoWithParams(stdClass $request): ?array
    {
        $qb = $this->createQueryBuilder('c');

        $select = $this->prepareSelectString($request);

        $qb->leftJoin('c.readings', 'r')
            ->leftJoin('c.courseScheduling', 'cs')
            ->leftJoin('c.teacher', 't')
            ->addSelect('r')
            ->addSelect('cs')
            ->addSelect('t')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('c.subjectCode', ':subjectCode'),
                    $qb->expr()->eq('c.courseTitle', ':courseTitle'),
                )
            )
            ->setParameter('subjectCode', $request->course)
            ->setParameter('courseTitle', $request->course);

        if ($select) {
            $qb->select(sprintf('DISTINCT %s', $select));
        }

        return $qb->getQuery()->getResult();
    }

    private function prepareSelectString(stdClass $request): string
    {
        $selectQuery = '';

        foreach ($request->filerParams as $key => $filter) {
            if ($this->checkKeyInArray($key)) {
                foreach ($filter as $item) {
                    if ($this->checkProperty($key, $item)) {
                        if (end($filter) === $item) {
                            if ($selectQuery) {
                                $selectQuery .= sprintf(', %s.%s', self::PROPERTY_ARRAY[$key], $item);
                            } else {
                                $selectQuery .= sprintf('%s.%s', self::PROPERTY_ARRAY[$key], $item);
                            }
                        } else {
                            if ($selectQuery) {
                                $selectQuery .= sprintf(', %s.%s', self::PROPERTY_ARRAY[$key], $item);
                            } else {
                                $selectQuery .= sprintf('%s.%s', self::PROPERTY_ARRAY[$key], $item);
                            }
                        }
                    }
                }
            }
        }

        return $selectQuery;
    }

    #[Pure] private function checkKeyInArray(string $key): bool
    {
        return array_key_exists($key, self::PROPERTY_ARRAY);
    }

    private function checkProperty(string $key, string $item): bool
    {
        return property_exists(self::OBJECT_PROPERTY_ARRAY[$key], $item);
    }
}
