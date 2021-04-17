<?php
declare(strict_types=1);

namespace App\DTO;

/**
 * Class TeacherDTO
 * @package App\DTO
 */
class TeacherDTO
{
    private int $id;
    private string $name;
    private ?string $externalId;
    private ?string $email;
    private ?string $officeNumber;
    private ?string $phoneNumber;
    private ?string $teacherUri;

    /** @var CourseDTO[] */
    private array $courses;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getOfficeNumber(): ?string
    {
        return $this->officeNumber;
    }

    public function setOfficeNumber(?string $officeNumber): self
    {
        $this->officeNumber = $officeNumber;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getTeacherUri(): ?string
    {
        return $this->teacherUri;
    }

    public function setTeacherUri(?string $teacherUri): self
    {
        $this->teacherUri = $teacherUri;

        return $this;
    }

    public function getCourses(): array
    {
        return $this->courses;
    }

    public function setCourses(array $courses): self
    {
        $this->courses = $courses;

        return $this;
    }
}
