<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\StudyProgramAim;
use App\Repository\StudyProgramAimRepository;

/**
 * Class StudyProgramAimService
 * @package App\Services
 */
class StudyProgramAimService
{
    private StudyProgramAimRepository $studyProgramAimRepository;

    public function __construct(StudyProgramAimRepository $studyProgramAimRepository)
    {
        $this->studyProgramAimRepository = $studyProgramAimRepository;
    }

    /**
     * @return StudyProgramAim[]
     */
    public function getAll(): array
    {
        return $this->studyProgramAimRepository->getAll();
    }

    public function getByStudyProgramId(int $studyProgramId): array
    {
        return $this->studyProgramAimRepository->getByStudyProgramId($studyProgramId);
    }
}
