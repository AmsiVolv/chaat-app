<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Course;
use App\Entity\CourseSheduling;
use App\Entity\Reading;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\CourseShedulingRepository;
use App\Repository\ReadingRepository;
use App\Repository\TeacherRepository;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Throwable;

/**
 * Class CourseService
 * @package App\Services
 */
class CourseService
{
    public const IS_GET_ALL = 'isGetAll';
    public const IS_GET_BY = 'isGetBy';
    public const SEARCHED_PARAMS = 'searchedParams';

    private CourseRepository $courseRepository;
    private TeacherRepository $teacherRepository;
    private CourseShedulingRepository $courseSchedulingRepository;
    private ReadingRepository $readingRepository;

    public function __construct(
        CourseRepository $courseRepository,
        TeacherRepository $teacherRepository,
        CourseShedulingRepository $courseSchedulingRepository,
        ReadingRepository $readingRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->teacherRepository = $teacherRepository;
        $this->courseSchedulingRepository = $courseSchedulingRepository;
        $this->readingRepository = $readingRepository;
    }

    /**
     * @param stdClass $request
     * @return Course|null
     * @throws Throwable
     */
    public function getCourseInfo(stdClass $request): ?array
    {
        $data = [];

        if (property_exists($request, 'filterParams') && $request->filterParams) {
            $filterParams = $request->filterParams;

            foreach ($filterParams as $key => $filter) {
                if ($filter === []) {
                    unset($filterParams[$key]);
                } else {
                    $filter[] = 'id';

                    if ($key === CourseRepository::COURSE) {
                        $data[CourseRepository::COURSE] = $this->courseRepository->getWithString(
                            $this->prepareSelectString([$key => $filter]),
                            $request
                        );
                    }
                    if ($key === CourseRepository::TEACHER) {
                        $data[CourseRepository::TEACHER] = $this->teacherRepository->getWithString(
                            $this->prepareSelectString([$key => $filter]),
                            $request
                        );
                    }
                    if ($key === CourseRepository::COURSE_SCHEDULING) {
                        $data[CourseRepository::COURSE_SCHEDULING] = $this->courseSchedulingRepository->getWithString(
                            $this->prepareSelectString([$key => $filter]),
                            $request
                        );
                    }
                    if ($key === CourseRepository::READING) {
                        $data[CourseRepository::READING] = $this->readingRepository->getWithString(
                            $this->prepareSelectString([$key => $filter]),
                            $request
                        );
                    }
                }
            }
        } else {
            $courseId = $this->courseRepository->getCourseIdByCourseName($request->course);

            if ($courseId) {
                $data[CourseRepository::COURSE] = $this->courseRepository->getAllByCourseId($courseId);
                $data[CourseRepository::READING] = $this->readingRepository->getAllByCourseId($courseId);
                $data[CourseRepository::COURSE_SCHEDULING] = $this->courseSchedulingRepository->getAllByCourseId($courseId);
                $data[CourseRepository::TEACHER] = $this->teacherRepository->getAllByCourseId($courseId);
            }
        }

        return $data;
    }

    /**
     * @param stdClass $request
     * @return Course|null
     * @throws Throwable
     */
    public function getFilterOptions(stdClass $request): ?array
    {
        $returnData = [];
        $data = $this->courseRepository->getCourseInfo($request->course);

        if ($data !== []) {
            /** @var Course $course */
            $course = $data[0];
            foreach ($course->getKeys() as $item) {
                if ($item !== 'readings' && $item !== 'courseScheduling' && $item !== 'teacher') {
                    $returnData[CourseRepository::COURSE][] = $item;
                }
                if ($item === 'readings') {
                    /** @var Reading $reading */
                    if ($course->getReadings() !== []) {
                        $reading = $course->getReadings()[0];
                        if ($reading) {
                            foreach ($reading->getKeys() as $readingItem) {
                                $returnData[CourseRepository::READING][] = $readingItem;
                            }
                        }
                    }
                }
                if ($item === 'courseScheduling') {
                    /** @var CourseSheduling $courseScheduling */
                    if ($course->getCourseScheduling() !== []) {
                        $courseScheduling = $course->getCourseScheduling()[0];
                        if ($courseScheduling) {
                            foreach ($courseScheduling->getKeys() as $courseSchedulingItem) {
                                $returnData[CourseRepository::COURSE_SCHEDULING][] = $courseSchedulingItem;
                            }
                        }
                    }
                }
                if ($item === 'teacher') {
                    /** @var Teacher $teacher */
                    if ($course->getTeacher() !== []) {
                        $teacher = $course->getTeacher()[0];
                        if ($teacher) {
                            foreach ($teacher->getKeys() as $teacherItem) {
                                $returnData[CourseRepository::TEACHER][] = $teacherItem;
                            }
                        }
                    }
                }
            }
        }

        return $returnData;
    }

    public function getAllCursesCodes(): array
    {
        return $this->courseRepository->getAllCursesCodes();
    }

    public function getAllCourseByParam(stdClass $data): array
    {
        $returnData = [];

        if (array_key_exists(self::IS_GET_ALL, $data->getAll) && $data->getAll[self::IS_GET_ALL]) {
            if ($this->checkRequestForGelAllByParam($data->getAll, CourseRepository::FACULTY)) {
                $returnData = $this->courseRepository->getAllCourseByFaculty($data->getAll[CourseRepository::FACULTY]);
            }
            if ($this->checkRequestForGelAllByParam($data->getAll, CourseRepository::READING)) {
                $returnData = $this->courseRepository->getAllCourseByReading($data->getAll[CourseRepository::READING]);
            }
            if ($this->checkRequestForGelAllByParam($data->getAll, CourseRepository::TEACHER)) {
                $returnData = $this->courseRepository->getAllCourseByTeacher($data->getAll[CourseRepository::TEACHER]);
            }
        }

        return $returnData;
    }

    private function checkRequestForGelAllByParam(array $data, string $property): bool
    {
        $gettingBy = sprintf('%s%s', self::IS_GET_BY, ucfirst($property));

        return array_key_exists($property, $data)
                && array_key_exists(self::SEARCHED_PARAMS, $data[$property])
                && $data[$property][self::SEARCHED_PARAMS] !== []
                && $data[$property] !== []
                && $data[$property][$gettingBy];
    }

    public function getCourseByRequest(stdClass $data): array
    {
        return $this->courseRepository->getCourseByRequest($data->course);
    }

    public function getCourseInfoByCourseId(?int $courseId): array
    {
        $data = [];
        $courseId = (string) $courseId;

        if ($courseId) {
            $data[CourseRepository::COURSE] = $this->courseRepository->getAllByCourseId($courseId);
            $data[CourseRepository::READING] = $this->readingRepository->getAllByCourseId($courseId);
            $data[CourseRepository::COURSE_SCHEDULING] = $this->courseSchedulingRepository->getAllByCourseId($courseId);
            $data[CourseRepository::TEACHER] = $this->teacherRepository->getAllByCourseId($courseId);
        }

        return $data;
    }

    private function prepareSelectString(array $filterParams): string
    {
        $selectQuery = '';

        foreach ($filterParams as $key => $filter) {
            if ($this->checkKeyInArray($key)) {
                foreach ($filter as $item) {
                    if ($this->checkPropertyFromRequest($key, $item)) {
                        if ($selectQuery) {
                            $selectQuery .= sprintf(', %s.%s', CourseRepository::PROPERTY_ARRAY[$key], $item);
                        } else {
                            $selectQuery .= sprintf('%s.%s', CourseRepository::PROPERTY_ARRAY[$key], $item);
                        }
                    }
                }
            }
        }

        return $selectQuery;
    }

    #[Pure] private function checkKeyInArray(string $key): bool
    {
        return array_key_exists($key, CourseRepository::PROPERTY_ARRAY);
    }

    private function checkPropertyFromRequest(string $key, string $item): bool
    {
        return property_exists(CourseRepository::OBJECT_PROPERTY_ARRAY[$key], $item);
    }
}
