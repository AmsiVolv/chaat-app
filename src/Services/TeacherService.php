<?php
declare(strict_types=1);

namespace App\Services;

use App\Assembler\CourseAssembler;
use App\Assembler\TeacherAssembler;
use App\DTO\TeacherDTO;
use App\Repository\CourseRepository;
use App\Repository\TeacherRepository;
use Throwable;

/**
 * Class TeacherService
 * @package App\Services
 */
class TeacherService
{
    private TeacherRepository $teacherRepository;
    private CourseRepository $courseRepository;
    private TeacherAssembler $teacherAssembler;

    public function __construct(
        TeacherRepository $teacherRepository,
        CourseRepository $courseRepository,
        TeacherAssembler $teacherAssembler
    ) {
        $this->teacherRepository = $teacherRepository;
        $this->courseRepository = $courseRepository;
        $this->teacherAssembler = $teacherAssembler;
    }

    public function getByTeacherName(?string $teacherName): array
    {
        $data = [];

        if ($teacherName) {
            $data = $this->teacherRepository->getByName($teacherName);
        }

        return $data;
    }

    /**
     * @param int|null $teacherId
     * @return TeacherDTO
     * @throws Throwable
     */
    public function getByTeacherId(?int $teacherId): TeacherDTO
    {
        $data = [];

        if ($teacherId) {
            $data = $this->teacherRepository->getById($teacherId);
            $data = $this->teacherAssembler->toDto($data);
        }

        return $data;
    }
}
