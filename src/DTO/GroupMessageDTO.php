<?php
declare(strict_types=1);

namespace App\DTO;

use DateTime;

/**
 * Class GroupMessageDTO
 * @package App\DTO
 */
class GroupMessageDTO
{
    private int $id;
    private int $groupId;
    private string $content;
    private string $authorName;
    private string $iconColor;
    private DateTime $createdAt;
    private string $groupName;
    private string $groupIconColor;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getIconColor(): string
    {
        return $this->iconColor;
    }

    public function setIconColor(string $iconColor): self
    {
        $this->iconColor = $iconColor;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getGroupIconColor(): string
    {
        return $this->groupIconColor;
    }

    public function setGroupIconColor(string $groupIconColor): self
    {
        $this->groupIconColor = $groupIconColor;

        return $this;
    }
}
