<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * Class Conversation
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ConversationRepository")
 * @ORM\Table(indexes={@Index(name="last_message_id_index", columns={"last_message_id"})})
 */
class Conversation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**     * @var Collection<Participant>
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="conversation", cascade={"persist", "remove"})
     */
    private Collection $participants;

    /**
     * @ORM\OneToOne(targetEntity="Message", cascade={"remove"})
     * @ORM\JoinColumn(name="last_message_id", referencedColumnName="id")
     */
    private ?Message $lastMessage = null;

    /** @ORM\OneToMany(targetEntity="Message", mappedBy="conversation"), cascade={"persist", "remove"} */
    private Collection $messages;

    use Timestamp;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setConversation($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getConversation() === $this) {
                $participant->setConversation(null);
            }
        }

        return $this;
    }

    public function getLastMessage(): ?Message
    {
        return $this->lastMessage;
    }

    public function setLastMessage(Message $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    public function removeLastMessage(): self
    {
        $this->lastMessage = null;

        return $this;
    }

    /**
     * @return Collection<Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }
}
