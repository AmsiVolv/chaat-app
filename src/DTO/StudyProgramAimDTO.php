<?php
declare(strict_types=1);

namespace App\DTO;

/**
 * Class StudyProgramAimDTO
 * @package App\DTO
 */
class StudyProgramAimDTO
{
    private int $id;
    private string $aim;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAim(): string
    {
        return $this->aim;
    }

    public function setAim(string $aim): self
    {
        $this->aim = $aim;

        return $this;
    }
}
