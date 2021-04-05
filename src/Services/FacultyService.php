<?php
declare(strict_types=1);

namespace App\Services;

use App\Repository\FacultyRepository;

/**
 * Class FacultyService
 * @package App\Services
 */
class FacultyService
{
    private FacultyRepository $facultyRepository;

    public function __construct(FacultyRepository $facultyRepository)
    {
        $this->facultyRepository = $facultyRepository;
    }

    public function getAllStudyAdmission(): array
    {
        return $this->facultyRepository->getAllStudyAdmission();
    }
}
