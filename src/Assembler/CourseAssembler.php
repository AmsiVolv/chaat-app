<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\CourseDTO;
use App\Entity\Course;

/**
 * Class CourseAssembler
 * @package App\Assembler
 */
class CourseAssembler
{
    public function toDtoWithoutRelation(Course $course): CourseDTO
    {
        return (new CourseDTO)
            ->setId($course->getId())
            ->setSubjectCode($course->getSubjectCode())
            ->setCourseTitle($course->getCourseTitle())
            ->setCourseLevelAndYearOfStudy($course->getCourseLevelAndYearOfStudy())
            ->setCourseRecommendation($course->getCourseRecommendation())
            ->setCourseContent($course->getCourseContent())
            ->setCourseAims($course->getCourseAims())
            ->setCourseUrl($course->getCourseUrl());
    }
}
