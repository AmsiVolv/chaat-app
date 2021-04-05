<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrialTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrialTestRepository::class)
 */
class TrialTest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $trialTestTitle;

    /** @ORM\Column(type="string", length=255) */
    private string $trialTestLink;

    /** @ORM\Column(type="string", length=255) */
    private string $keyword;

    public function __construct(string $trialTestTitle, string $trialTestLink, string $keyword)
    {
        $this->trialTestTitle = $trialTestTitle;
        $this->trialTestLink = $trialTestLink;
        $this->keyword = $keyword;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrialTestTitle(): ?string
    {
        return $this->trialTestTitle;
    }

    public function setTrialTestTitle(string $trialTestTitle): self
    {
        $this->trialTestTitle = $trialTestTitle;

        return $this;
    }

    public function getTrialTestLink(): ?string
    {
        return $this->trialTestLink;
    }

    public function setTrialTestLink(string $trialTestLink): self
    {
        $this->trialTestLink = $trialTestLink;

        return $this;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }
}
