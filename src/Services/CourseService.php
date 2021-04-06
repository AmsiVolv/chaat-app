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
            $results = $this->courseRepository->getCourseInfoWithParams($request);

            foreach ($results as $result) {
                $data = array_merge_recursive($data, $result);
            }

            // Check if only one filter if yes return data
            if (count($request->filterParams) === 1 || count($data) === 1) {
                $dataToReturn = [];

                foreach ($request->filterParams as $key => $filter) {
                    $dataToReturn[$key] = $data;
                }

                return $dataToReturn;
            }

            foreach ($data as $key => $item) {
                if (is_array($item)) {
                    $data[$key] = array_values(array_unique($item));
                } else {
                    $data[$key] = $item;
                }
            }

            $data = $this->checkProperty($data);
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

    private function checkProperty(array $data): array
    {
        $returnData = [];

        foreach ($data as $key => $item) {
            switch ($key) {
                case property_exists(Course::class, $key):
                    $item = $this->checkLengthAndReturnPropertyValue($item);
                    $returnData[CourseRepository::COURSE][$key] = $item;
                    break;
                case property_exists(Reading::class, $key):
                    $item = $this->checkLengthAndReturnPropertyValue($item);
                    $returnData[CourseRepository::READING][$key] = $item;
                    break;
                case property_exists(CourseSheduling::class, $key):
                    $item = $this->checkLengthAndReturnPropertyValue($item);
                    $returnData[CourseRepository::COURSE_SCHEDULING][$key] = $item;
                    break;
                case property_exists(Teacher::class, $key):
                    $item = $this->checkLengthAndReturnPropertyValue($item);
                    $returnData[CourseRepository::TEACHER][$key] = $item;
                    break;
            }
        }

        return array_merge($returnData, $this->sortResultArray($returnData));
    }

    private function checkLengthAndReturnPropertyValue(string|array $itemForCheck): string|array
    {
        if (!is_array($itemForCheck)) {
            $itemForCheck = (string) $itemForCheck;
        }

        return $itemForCheck;
    }

    private function sortResultArray(array $inputData): array
    {
        $dataTorReturn = [];

        foreach ($inputData as $mainNodeKey => $mainNodeValue) {
            foreach ($mainNodeValue as $childNodeKey => $childNodeValue) {
                if (is_array($childNodeValue)) {
                    foreach ($childNodeValue as $valueKey => $valueVal) {
                        $dataTorReturn[$mainNodeKey][$valueKey][$childNodeKey] = $valueVal;
                    }
                }
            }
        }

        return $dataTorReturn;
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
}
