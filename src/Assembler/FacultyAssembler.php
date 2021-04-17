<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\FacultyDTO;
use App\Entity\Faculty;

/**
 * Class FacultyAssembler
 * @package App\Assembler
 */
class FacultyAssembler
{
    private StudyProgramAssembler $studyProgramAssembler;

    public function __construct(StudyProgramAssembler $studyProgramAssembler)
    {
        $this->studyProgramAssembler = $studyProgramAssembler;
    }

    public function toDtoWithOutCourseInformation(Faculty $faculty): FacultyDTO
    {
        $studyPrograms = [];

        foreach ($faculty->getStudyPrograms() as $studyProgram) {
            $studyPrograms[] = $this->studyProgramAssembler->toDto($studyProgram);
        }

        return (new FacultyDTO())
            ->setId($faculty->getId())
            ->setFacultyName($faculty->getFacultyName())
            ->setLogoLink($faculty->getLogoLink())
            ->setAttainmentLink($faculty->getAttainmentLink())
            ->setWebLink($faculty->getWebLink())
            ->setAbbreviation($faculty->getAbbreviation())
            ->setPeriod($faculty->getPeriod())
            ->setStudyAdmissionLink($faculty->getStudyAdmissionLink() ?? '')
            ->setStudyPrograms($studyPrograms);
    }
}
