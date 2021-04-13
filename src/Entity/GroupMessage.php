<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroupMessageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupMessageRepository::class)
 */
class GroupMessage
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $messageContent;

    /** @ORM\ManyToOne(targetEntity=User::class) */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=GroupConversation::class, inversedBy="groupMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?GroupConversation $groupConversation;

    public function __construct(string $messageContent, ?User $user, ?GroupConversation $groupConversation)
    {
        $this->messageContent = $messageContent;
        $this->user = $user;
        $this->groupConversation = $groupConversation;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageContent(): ?string
    {
        return $this->messageContent;
    }

    public function setMessageContent(string $messageContent): self
    {
        $this->messageContent = $messageContent;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroupConversation(): ?GroupConversation
    {
        return $this->groupConversation;
    }

    public function setGroupConversation(?GroupConversation $groupConversation): self
    {
        $this->groupConversation = $groupConversation;

        return $this;
    }
}
