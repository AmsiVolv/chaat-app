<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\StudyProgram;
use App\Repository\StudyProgramRepository;

/**
 * Class StudyProgramService
 * @package App\Services
 */
class StudyProgramService
{
    private StudyProgramRepository $studyProgramRepository;

    public function __construct(StudyProgramRepository $studyProgramRepository)
    {
        $this->studyProgramRepository = $studyProgramRepository;
    }

    /**
     * @return StudyProgram[]
     */
    public function getAll(): array
    {
        return $this->studyProgramRepository->getAll();
    }
}
