<?php
declare(strict_types=1);

namespace App\Services;

use App\Controller\CheckRequestDataTrait;
use App\Entity\StudyProgram;
use App\Repository\StudyProgramRepository;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class StudyProgramService
 * @package App\Services
 */
class StudyProgramService
{
    private StudyProgramRepository $studyProgramRepository;
    private StudyProgramLanguageService $studyProgramLanguageService;
    private StudyProgramAimService $studyProgramAimService;
    private StudyProgramFormTypeService $studyProgramFormTypeService;

    use CheckRequestDataTrait;

    public function __construct(
        StudyProgramRepository $studyProgramRepository,
        StudyProgramLanguageService $studyProgramLanguageService,
        StudyProgramAimService $studyProgramAimService,
        StudyProgramFormTypeService $studyProgramFormTypeService,
    ) {
        $this->studyProgramRepository = $studyProgramRepository;
        $this->studyProgramLanguageService = $studyProgramLanguageService;
        $this->studyProgramAimService = $studyProgramAimService;
        $this->studyProgramFormTypeService = $studyProgramFormTypeService;
    }

    /**
     * @throws Throwable
     * @return array
     */
    public function getAll(): array
    {
        $data = $this->studyProgramRepository->getAll();
        foreach ($data as &$studyProgram) {
            if ($studyProgram) {
                $aimString = '';
                $aims = $this->studyProgramAimService->getByStudyProgramId($studyProgram['studyProgramId']);
                foreach ($aims as $aim) {
                    $aimString .= sprintf(' %s ', $aim['aim']);
                }
                $studyProgram['aims'] = sprintf('%s: %s', 'Zaměření', str_replace('  ', ', ', trim($aimString)));
            }
        }

        return $data;
    }

    #[ArrayShape([
        'languageFilterOptions' => "\App\Entity\StudyProgram[]",
        'studyFormFilterOptions' => "\App\Entity\StudyProgramFormType[]",
        'studyProgramAimFilterOptions' => "\App\Entity\StudyProgramAim[]"])]
    public function getFilterOptions(): array
    {
        return [
            'languageFilterOptions' => $this->studyProgramLanguageService->getAll(),
            'studyFormFilterOptions' => $this->studyProgramFormTypeService->getAll(),
            'studyProgramAimFilterOptions' => $this->studyProgramAimService->getAll(),
        ];
    }
}
