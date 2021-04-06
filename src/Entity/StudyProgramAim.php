<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\StudyProgramAimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudyProgramAimRepository::class)
 */
class StudyProgramAim
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private string $aim;

    /** @ORM\ManyToMany(targetEntity=StudyProgram::class, mappedBy="aims") */
    private $studyPrograms;

    public function __construct(string $aim)
    {
        $this->aim = $aim;
        $this->studyPrograms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAim(): ?string
    {
        return $this->aim;
    }

    public function setAim(string $aim): self
    {
        $this->aim = $aim;

        return $this;
    }

//    /**
//     * @return Collection|StudyProgram[]
//     */
//    public function getStudyPrograms(): Collection
//    {
//        return $this->studyPrograms;
//    }

    public function addStudyProgram(StudyProgram $studyProgram): self
    {
        if (!$this->studyPrograms->contains($studyProgram)) {
            $this->studyPrograms[] = $studyProgram;
            $studyProgram->addAim($this);
        }

        return $this;
    }

    public function removeStudyProgram(StudyProgram $studyProgram): self
    {
        if ($this->studyPrograms->removeElement($studyProgram)) {
            $studyProgram->removeAim($this);
        }

        return $this;
    }
}
