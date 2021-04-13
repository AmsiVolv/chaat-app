<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\SchoolAreaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SchoolAreaRepository::class)
 */
class SchoolArea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private $areaTitle;

    /** @ORM\Column(type="string", length=255) */
    private $areaMenuLink;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAreaTitle(): ?string
    {
        return $this->areaTitle;
    }

    public function setAreaTitle(string $areaTitle): self
    {
        $this->areaTitle = $areaTitle;

        return $this;
    }

    public function getAreaMenuLink(): ?string
    {
        return $this->areaMenuLink;
    }

    public function setAreaMenuLink(string $areaMenuLink): self
    {
        $this->areaMenuLink = $areaMenuLink;

        return $this;
    }
}
