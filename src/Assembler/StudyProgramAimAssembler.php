<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\StudyProgramAimDTO;
use App\Entity\StudyProgramAim;

/**
 * Class StudyProgramAimAssembler
 * @package App\Assembler
 */
class StudyProgramAimAssembler
{
    public function toDto(StudyProgramAim $programAim): StudyProgramAimDTO
    {
        return (new StudyProgramAimDTO)
            ->setId($programAim->getId())
            ->setAim($programAim->getAim());
    }
}
