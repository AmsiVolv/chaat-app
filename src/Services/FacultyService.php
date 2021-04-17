<?php
declare(strict_types=1);

namespace App\Services;

use App\Assembler\FacultyAssembler;
use App\Repository\FacultyRepository;

/**
 * Class FacultyService
 * @package App\Services
 */
class FacultyService
{
    private FacultyRepository $facultyRepository;
    private FacultyAssembler $facultyAssembler;

    public function __construct(FacultyRepository $facultyRepository, FacultyAssembler $facultyAssembler)
    {
        $this->facultyRepository = $facultyRepository;
        $this->facultyAssembler = $facultyAssembler;
    }

    public function getAllStudyAdmission(): array
    {
        return $this->facultyRepository->getAllStudyAdmission();
    }

    public function getAll(): array
    {
        $data = [];
        $faculties = $this->facultyRepository->getAll();

        foreach ($faculties as $faculty) {
            $data[] = $this->facultyAssembler->toDtoWithOutCourseInformation($faculty);
        }

        return $data;
    }
}
