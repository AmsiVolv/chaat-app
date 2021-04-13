<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    use Timestamp;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=SchoolArea::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private SchoolArea $schoolArea;

    /** @ORM\OneToMany(targetEntity=MenuMeals::class, mappedBy="menu") */
    private $mealContent;

    public function __construct(SchoolArea $schoolArea)
    {
        $this->schoolArea = $schoolArea;
        $this->createdAt = new \DateTime();
        $this->mealContent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolArea(): ?SchoolArea
    {
        return $this->schoolArea;
    }

    public function setSchoolArea(?SchoolArea $schoolArea): self
    {
        $this->schoolArea = $schoolArea;

        return $this;
    }

    /**
     * @return Collection|MenuMeals[]
     */
    public function getMealContent(): Collection
    {
        return $this->mealContent;
    }

    public function addMealContent(MenuMeals $mealContent): self
    {
        if (!$this->mealContent->contains($mealContent)) {
            $this->mealContent[] = $mealContent;
            $mealContent->setMenu($this);
        }

        return $this;
    }

    public function removeMealContent(MenuMeals $mealContent): self
    {
        if ($this->mealContent->removeElement($mealContent)) {
            // set the owning side to null (unless already changed)
            if ($mealContent->getMenu() === $this) {
                $mealContent->setMenu(null);
            }
        }

        return $this;
    }
}
