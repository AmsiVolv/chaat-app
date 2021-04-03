<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\StudyProgramFormType;
use App\Repository\StudyProgramFormTypeRepository;

/**
 * Class StudyProgramFormTypeService
 * @package App\Services
 */
class StudyProgramFormTypeService
{
    private StudyProgramFormTypeRepository $studyProgramFormTypeRepository;

    public function __construct(StudyProgramFormTypeRepository $studyProgramFormTypeRepository)
    {
        $this->studyProgramFormTypeRepository = $studyProgramFormTypeRepository;
    }

    /**
     * @return StudyProgramFormType[]
     */
    public function getAll(): array
    {
        return $this->studyProgramFormTypeRepository->getAll();
    }
}
