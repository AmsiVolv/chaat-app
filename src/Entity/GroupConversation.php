<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroupConversationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=GroupConversationRepository::class)
 */
class GroupConversation
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $groupName;

    /** @ORM\Column(type="string", length=255) */
    private string $groupColor;

    /**
     * @var Collection<GroupMessage> $groupMessages
     * @ORM\OneToMany(targetEntity=GroupMessage::class, mappedBy="groupConversation", orphanRemoval=true)
     */
    private Collection $groupMessages;

    /**
     * @var Collection<User> $user
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="groupConversations")
     */
    private Collection $user;

    /** @ORM\OneToOne(targetEntity=Course::class, cascade={"persist", "remove"}) */
    private ?Course $course;

    /** @ORM\Column(type="integer") */
    private int $maxMemberCount;

    /** @ORM\Column(type="datetime") */
    private DateTime $updatedAt;

    /** @ORM\OneToOne(targetEntity=GroupMessage::class, cascade={"persist", "remove"}) */
    private ?GroupMessage $lastMessage;

    public function __construct(
        string $groupName,
        string $groupColor,
        int $maxMemberCount,
        ?Course $course
    ) {
        $this->groupName = $groupName;
        $this->groupColor = $groupColor;
        $this->maxMemberCount = $maxMemberCount;
        $this->course = $course;

        $this->groupMessages = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getGroupColor(): ?string
    {
        return $this->groupColor;
    }

    public function setGroupColor(string $groupColor): self
    {
        $this->groupColor = $groupColor;

        return $this;
    }

    /**
     * @return Collection|GroupMessage[]
     */
    public function getGroupMessages(): Collection
    {
        return $this->groupMessages;
    }

    public function addGroupMessage(GroupMessage $groupMessage): self
    {
        if (!$this->groupMessages->contains($groupMessage)) {
            $this->groupMessages[] = $groupMessage;
            $groupMessage->setGroupConversation($this);
        }

        return $this;
    }

    public function removeGroupMessage(GroupMessage $groupMessage): self
    {
        if ($this->groupMessages->removeElement($groupMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupMessage->getGroupConversation() === $this) {
                $groupMessage->setGroupConversation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getMaxMemberCount(): ?int
    {
        return $this->maxMemberCount;
    }

    public function setMaxMemberCount(int $maxMemberCount): self
    {
        $this->maxMemberCount = $maxMemberCount;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastMessage(): ?GroupMessage
    {
        return $this->lastMessage;
    }

    public function setLastMessage(?GroupMessage $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }
}
