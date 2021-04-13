<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenuMealsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuMealsRepository::class)
 */
class MenuMeals
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private string $mealName;

    /** @ORM\Column(type="string", length=255) */
    private string $mealContent;

    /** @ORM\ManyToOne(targetEntity=Menu::class, inversedBy="mealContent") */
    private Menu $menu;

    public function __construct(string $mealName, string $mealContent, Menu $menu)
    {
        $this->mealName = $mealName;
        $this->mealContent = $mealContent;
        $this->menu = $menu;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMealName(): ?string
    {
        return $this->mealName;
    }

    public function setMealName(string $mealName): self
    {
        $this->mealName = $mealName;

        return $this;
    }

    public function getMealContent(): ?string
    {
        return $this->mealContent;
    }

    public function setMealContent(string $mealContent): self
    {
        $this->mealContent = $mealContent;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
