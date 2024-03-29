<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\CourseShedulingRepository;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;
use ReflectionProperty;

/**
 * @ORM\Entity(repositoryClass=CourseShedulingRepository::class)
 */
class CourseSheduling
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $day;

    /** @ORM\Column(type="string", length=255) */
    private string $time;

    /** @ORM\Column(type="string", length=255) */
    private string $room;

    /** @ORM\Column(type="string", length=255) */
    private string $eventType;

    /** @ORM\Column(type="integer") */
    private int $capacity;

    /** @ORM\ManyToOne(targetEntity=Course::class, inversedBy="courseShedulings") */
    private $course;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(string $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

//    public function getCourse(): ?Course
//    {
//        return $this->course;
//    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return ReflectionProperty[]
     */
    public function getKeys(): array
    {
        return [
            'day',
            'time',
            'room',
            'eventType',
            'capacity',
        ];
    }

    public static function getPrimaryKeys(): array
    {
        return [
            'id',
            'day',
            'time',
            'room',
            'eventType',
            'capacity',
        ];
    }
}
