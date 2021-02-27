<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\UserDto;
use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\ConversationRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class ParticipantService
 * @package App\Services
 */
class ParticipantService
{
    private ParticipantRepository $participantRepository;

    public function __construct(ParticipantRepository $participantRepository)
    {
        $this->participantRepository = $participantRepository;
    }

    public function findParticipantByConversationIdAndUserId(int $conversationId, int $userId): Participant
    {
        return $this->participantRepository->find(['id' => 1]);
//        return $this->participantRepository->findParticipantByConversationIdAndUserId($conversationId, $userId);
    }
}
