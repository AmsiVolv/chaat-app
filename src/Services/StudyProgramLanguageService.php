<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\StudyProgram;
use App\Repository\StudyProgramLanguageRepository;

/**
 * Class StudyProgramLanguageService
 * @package App\Services
 */
class StudyProgramLanguageService
{
    private StudyProgramLanguageRepository $studyProgramLanguageRepository;

    public function __construct(StudyProgramLanguageRepository $studyProgramRepository)
    {
        $this->studyProgramLanguageRepository = $studyProgramRepository;
    }

    /**
     * @return StudyProgram[]
     */
    public function getAll(): array
    {
        return $this->studyProgramLanguageRepository->getAll();
    }
}
