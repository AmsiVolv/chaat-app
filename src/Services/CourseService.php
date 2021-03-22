<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Course;
use App\Entity\CourseSheduling;
use App\Entity\Reading;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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

    public function __construct(
        CourseRepository $courseRepository,
    ) {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param stdClass $request
     * @return Course|null
     * @throws Throwable
     */
    public function getCourseInfo(stdClass $request): ?array
    {
        $data = [];

        if (property_exists($request, 'filerParams') && $request->filerParams) {
            $results = $this->courseRepository->getCourseInfoWithParams($request);

            foreach ($results as $result) {
                $data = array_merge_recursive($data, $result);
            }
            foreach ($data as $key => $item) {
                $data[$key] = array_values(array_unique($item));
            }
            $data = $this->checkProperty($data);
        } else {
            $data = $this->courseRepository->getCourseInfo($request->course);
        }

        return $data;
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

    private function checkLengthAndReturnPropertyValue(array $itemForCheck): string|array
    {
        if (count($itemForCheck) === 1) {
            $itemForCheck = trim((string) $itemForCheck[0]);
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
}
