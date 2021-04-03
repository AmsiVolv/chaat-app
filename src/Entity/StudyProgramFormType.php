<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\StudyProgramFormTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudyProgramFormTypeRepository::class)
 */
class StudyProgramFormType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private string $form;

    /** @ORM\OneToMany(targetEntity=StudyProgram::class, mappedBy="studyProgramForm") */
    private $studyPrograms;

    public function __construct(string $form)
    {
        $this->form = $form;
        $this->studyPrograms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }

    public function setForm(string $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return Collection|StudyProgram[]
     */
    public function getStudyPrograms(): Collection
    {
        return $this->studyPrograms;
    }

    public function addStudyProgram(StudyProgram $studyProgram): self
    {
        if (!$this->studyPrograms->contains($studyProgram)) {
            $this->studyPrograms[] = $studyProgram;
            $studyProgram->setStudyProgramForm($this);
        }

        return $this;
    }

    public function removeStudyProgram(StudyProgram $studyProgram): self
    {
        if ($this->studyPrograms->removeElement($studyProgram)) {
            // set the owning side to null (unless already changed)
            if ($studyProgram->getStudyProgramForm() === $this) {
                $studyProgram->setStudyProgramForm(null);
            }
        }

        return $this;
    }
}
