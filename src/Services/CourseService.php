<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Course;
use App\Entity\CourseSheduling;
use App\Entity\Reading;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
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
}
