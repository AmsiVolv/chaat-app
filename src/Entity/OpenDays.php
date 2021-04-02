<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OpenDaysRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpenDaysRepository::class)
 */
class OpenDays
{
    use Timestamp;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $openDaysDescription = '';

    /** @ORM\Column(type="string", length=255) */
    private string $openDayDate = '';

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $link = '';

    /** @ORM\Column(type="boolean") */
    private bool $todayChecked;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenDaysDescription(): ?string
    {
        return $this->openDaysDescription;
    }

    public function setOpenDaysDescription(string $openDaysDescription): self
    {
        $this->openDaysDescription = $openDaysDescription;

        return $this;
    }

    public function getOpenDayDate(): ?string
    {
        return $this->openDayDate;
    }

    public function setOpenDayDate(string $openDayDate): self
    {
        $this->openDayDate = $openDayDate;

        return $this;
    }

    public function getTodayChecked(): ?bool
    {
        return $this->todayChecked;
    }

    public function setTodayChecked(bool $todayChecked): self
    {
        $this->todayChecked = $todayChecked;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
