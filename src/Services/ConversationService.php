<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\UserDto;
use App\Entity\Conversation;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class ConversationService
 * @package App\Services
 */
class ConversationService
{
    private ConversationRepository $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function findByUserId(int $userId): Conversation
    {
        return $this->conversationRepository->find(['id' => 2]);
//        return $this->conversationRepository->findConversationsByUser($userId);
    }
}
