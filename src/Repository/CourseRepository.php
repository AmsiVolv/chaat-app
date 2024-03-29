<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Course;
use App\Entity\CourseSheduling;
use App\Entity\Reading;
use App\Entity\Teacher;
use App\Services\CourseService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public const COURSE = 'course';
    public const FACULTY = 'faculty';
    public const TEACHER = 'teacher';
    public const COURSE_SCHEDULING = 'courseScheduling';
    public const READING = 'reading';

    private const COURSE_ALIAS = 'c';
    public const FACULTY_ALIAS = 'f';
    public const TEACHER_ALIAS = 't';
    public const COURSE_SCHEDULING_ALIAS = 'cs';
    public const READING_ALIAS = 'r';

    public const PROPERTY_ARRAY = [
        self::COURSE => self::COURSE_ALIAS,
        self::TEACHER => self::TEACHER_ALIAS,
        self::COURSE_SCHEDULING => self::COURSE_SCHEDULING_ALIAS,
        self::READING => self::READING_ALIAS,
    ];

    public const OBJECT_PROPERTY_ARRAY = [
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
     * @param string $select
     * @return Course|null
     */
    public function getCourseInfoWithParams(stdClass $request, string $select): ?array
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
            ->setParameter('subjectCode', $request->course)
            ->setParameter('courseTitle', $request->course);

        if ($select) {
            $qb->select(sprintf('DISTINCT %s', $select));
        }

        return $qb->getQuery()->getResult();
    }

    public function getAllCursesCodes(): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('c.id as id, c.subjectCode as subjectCode');

        return $qb->getQuery()->getResult();
    }

    public function getAllCourseByFaculty(array $data): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->join('c.faculty', 'f');
        if (count($data[CourseService::SEARCHED_PARAMS]) === 2) {
            $qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('f.facultyName', ':facultyName'),
                    $qb->expr()->eq('f.abbreviation', ':abbreviation'),
                )
            )
                ->setParameter('facultyName', $data[CourseService::SEARCHED_PARAMS]['facultyName'])
                ->setParameter('abbreviation', $data[CourseService::SEARCHED_PARAMS]['abbreviation']);
        } else {
            foreach ($data[CourseService::SEARCHED_PARAMS] as $key => $searchedPram) {
                $qb->where($qb->expr()->like(sprintf('%s.%s', self::FACULTY_ALIAS, $key), sprintf(':%s', $key)))
                   ->setParameter($key, $searchedPram);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function getAllCourseByReading(array $data): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->join('c.readings', 'r');
        if (count($data[CourseService::SEARCHED_PARAMS]) === 2) {
            $qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('r.ISBN', ':ISBN'),
                    $qb->expr()->eq('r.author', ':author'),
                )
            )
                ->setParameter('ISBN', $data[CourseService::SEARCHED_PARAMS]['ISBN'])
                ->setParameter('author', $data[CourseService::SEARCHED_PARAMS]['author']);
        } else {
            foreach ($data[CourseService::SEARCHED_PARAMS] as $key => $searchedPram) {
                $qb->where($qb->expr()->like(sprintf('%s.%s', self::READING_ALIAS, $key), sprintf(':%s', $key)))
                    ->setParameter($key, $searchedPram);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function getAllCourseByTeacher(array $data): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->join('c.teacher', 't');
        if (count($data[CourseService::SEARCHED_PARAMS]) === 2) {
            $qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('t.name', ':name'),
                    $qb->expr()->eq('t.email', ':email'),
                )
            )
                ->setParameter('name', $data[CourseService::SEARCHED_PARAMS]['name'])
                ->setParameter('email', $data[CourseService::SEARCHED_PARAMS]['email']);
        } else {
            foreach ($data[CourseService::SEARCHED_PARAMS] as $key => $searchedPram) {
                $qb->where($qb->expr()->like(sprintf('%s.%s', self::TEACHER_ALIAS, $key), sprintf(':%s', $key)))
                    ->setParameter($key, $searchedPram);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function getCourseByRequest(string $course): ?array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('c.subjectCode, c.courseTitle, c.id')
            ->where($qb->expr()->orX(
                $qb->expr()->like('c.subjectCode', ':subjectCode'),
                $qb->expr()->like('c.courseTitle', ':courseTitle')
            ))
            ->setParameter('subjectCode', sprintf('%%%s%%', $course))
            ->setParameter('courseTitle', sprintf('%%%s%%', $course))
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function getAllByCourseId(string $courseId): ?array
    {
        $qb = $this->createQueryBuilder('c');
        $selectString = $this->getSelectQueryForCourse();

        $qb->select($selectString)
        ->where(
            $qb->expr()->eq('c.id', ':id')
        )
        ->setParameter('id', $courseId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $course
     * @return string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCourseIdByCourseName(string $course): string
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('c.id')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('c.subjectCode', ':subjectCode'),
                    $qb->expr()->eq('c.courseTitle', ':courseTitle'),
                )
            )
            ->setParameter('subjectCode', $course)
            ->setParameter('courseTitle', $course);

        return $qb->getQuery()->getSingleScalarResult();
    }

    private function getSelectQueryForCourse(): string
    {
        $selectString = '';
        $courseKeys = Course::getPrimaryKeys();

        foreach ($courseKeys as $courseKey) {
            $selectString .= sprintf(' %s.%s ', self::COURSE_ALIAS, $courseKey);
        }

        return str_replace('  ', ', ', trim($selectString));
    }

    public function getAllCourseByTeacherId(int $teacherId): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->join('c.teacher', 'r')
            ->where($qb->expr()->eq('r.id', ':id'))
            ->setParameter('id', $teacherId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $ident
     * @return Course|null
     * @throws Throwable
     */
    public function getCourseByIdent(string $ident): ?Course
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where($qb->expr()->eq('c.subjectCode', ':subjectCode'))
            ->setParameter('subjectCode', $ident);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getWithString(string $prepareSelectString, stdClass $request): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select($prepareSelectString)
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
