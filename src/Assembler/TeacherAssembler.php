<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\CourseDTO;
use App\DTO\TeacherDTO;
use App\Entity\Teacher;

/**
 * Class TeacherAssembler
 * @package App\Assembler
 */
class TeacherAssembler
{
    private CourseAssembler $courseAssembler;

    public function __construct(CourseAssembler $courseAssembler)
    {
        $this->courseAssembler = $courseAssembler;
    }
    public function toDto(Teacher $teacher): TeacherDTO
    {
//        private int $id;
//    private string $name;
//    private ?string $externalId;
//    private ?string $email;
//    private ?string $officeNumber;
//    private ?string $phoneNumber;
//    private ?string $teacherUri;
//
//    /** @var CourseDTO[] */
//    private array $courses;
        $coursesDto = [];

        foreach ($teacher->getCourses() as $course) {
            $coursesDto[] = $this->courseAssembler->toDtoWithoutRelation($course);
        }

        return (new TeacherDTO)
            ->setId($teacher->getId())
            ->setName($teacher->getName())
            ->setExternalId($teacher->getExternalId())
            ->setEmail($teacher->getEmail())
            ->setOfficeNumber($teacher->getOfficeNumber())
            ->setPhoneNumber($teacher->getPhoneNumber())
            ->setTeacherUri($teacher->getTeacherUri())
            ->setCourses($coursesDto);
    }
}
