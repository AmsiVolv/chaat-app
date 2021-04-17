<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\StudyProgramDTO;
use App\Entity\StudyProgram;

/**
 * Class StudyProgramAssembler
 * @package App\Assembler
 */
class StudyProgramAssembler
{
    private StudyProgramAimAssembler $aimAssembler;

    public function __construct(StudyProgramAimAssembler $aimAssembler)
    {
        $this->aimAssembler = $aimAssembler;
    }

    public function toDto(StudyProgram $studyProgram): StudyProgramDTO
    {
        $studyProgramAims = [];

        foreach ($studyProgram->getAims() as $aim) {
            $studyProgramAims[] = $this->aimAssembler->toDto($aim);
        }

        return (new StudyProgramDTO())
            ->setId($studyProgram->getId())
            ->setStudyProgramLanguage($studyProgram->getStudyProgramLanguage()->getLanguage())
            ->setStudyProgramForm($studyProgram->getStudyProgramForm()->getForm())
            ->setExternalId($studyProgram->getExternalId())
            ->setStudyProgramTitle($studyProgram->getStudyProgramTitle())
            ->setLink($studyProgram->getLink())
            ->setStudyProgramCapacity($studyProgram->getStudyProgramCapacity())
            ->setAims($studyProgramAims);
    }
}
