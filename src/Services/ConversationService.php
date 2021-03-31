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
use Symfony\Component\HttpFoundation\Request;
use Throwable;

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

    /**
     * @param int $userId
     * @param int $conversationId
     * @return bool
     */
    public function checkIfConversationExist(int $userId, int $conversationId): bool
    {
        return $this->conversationRepository->findConversationsByUserAndId($userId, $conversationId) !== [];
    }

    /**
     * @param int $conversationId
     * @throws ORMException
     * @throws Throwable
     */
    public function deleteConversation(int $conversationId): void
    {
        $this->conversationRepository->deleteConversation($conversationId);
    }

    /**
     * @param int $conversationId
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Throwable
     */
    public function clearConversation(int $conversationId): void
    {
        $this->conversationRepository->clearConversation($conversationId);
    }
}
