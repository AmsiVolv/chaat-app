<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\Table(indexes={@Index(name="created_at_index", columns={"created_at"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="text") */
    private string $content;

    /** @ORM\ManyToOne(targetEntity="User", inversedBy="messages") */
    private ?User $user = null;

    /** @ORM\ManyToOne(targetEntity="Conversation", inversedBy="messages" ) */
    private ?Conversation $conversation = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
