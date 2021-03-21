<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReadingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReadingRepository::class)
 */
class Reading
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private $readingType;

    /** @ORM\Column(type="text", nullable=true) */
    private $Author;

    /** @ORM\Column(type="text", nullable=true) */
    private $title;

    /** @ORM\Column(type="string", length=255) */
    private $ISBN;

    /** @ORM\Column(type="string", length=255) */
    private $libraryLink;

    /** @ORM\ManyToMany(targetEntity=Course::class, inversedBy="readings") */
    private $course;

    public function __construct()
    {
        $this->course = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReadingType(): ?string
    {
        return $this->readingType;
    }

    public function setReadingType(string $readingType): self
    {
        $this->readingType = $readingType;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(?string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getLibraryLink(): ?string
    {
        return $this->libraryLink;
    }

    public function setLibraryLink(string $libraryLink): self
    {
        $this->libraryLink = $libraryLink;

        return $this;
    }

//    /**
//     * @return Collection|Course[]
//     */
//    public function getCurse(): Collection
//    {
//        return $this->course;
//    }

    public function addCurse(Course $course): self
    {
        if (!$this->course->contains($course)) {
            $this->course[] = $course;
        }

        return $this;
    }

    public function removeCurse(Course $course): self
    {
        $this->course->removeElement($course);

        return $this;
    }
}
