<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\GroupConversation;
use App\Repository\GroupConversationRepository;
use App\Repository\GroupMessageRepository;
use App\Repository\UserRepository;
use Throwable;

/**
 * Class GroupConversationService
 * @package App\Services
 */
class GroupConversationService
{
    private GroupConversationRepository $groupConversationRepository;
    private UserRepository $userRepository;

    public function __construct(GroupConversationRepository $groupConversationRepository, UserRepository $userRepository)
    {
        $this->groupConversationRepository = $groupConversationRepository;
        $this->userRepository = $userRepository;
    }

    public function getByUserId(int $userId): array
    {
        return $this->groupConversationRepository->getByUserId($userId);
    }

    public function getMembersByGroupId(int $getGroupId): array
    {
        return $this->groupConversationRepository->getMembersByGroupId($getGroupId);
    }

    /**
     * @param int $userId
     * @param int $groupConversationId
     * @return bool
     * @throws Throwable
     */
    public function removeUserFromConversation(int $userId, int $groupConversationId): bool
    {
        if ($this->checkUserInConversation($userId, $groupConversationId)) {
            $groupConversation = $this->groupConversationRepository->getById($groupConversationId);
            $user = $this->userRepository->getUserById($userId);
            $groupConversation->removeUser($user);
            $this->groupConversationRepository->store($groupConversation);

            return true;
        }

        return false;
    }

    private function checkUserInConversation(int $userId, int $groupConversationId): bool
    {
        return $this->groupConversationRepository->getByUserIdAndConversationId($userId, $groupConversationId) !== [];
    }

    /**
     * @param int $userId
     * @param int $groupConversationId
     * @return GroupConversation|null
     * @throws Throwable
     */
    public function enterUserToConversation(int $userId, int $groupConversationId): ?GroupConversation
    {
        $groupConversation = null;
        if (!$this->checkUserInConversation($userId, $groupConversationId)) {
            $groupConversation = $this->groupConversationRepository->getById($groupConversationId);
            if (count($groupConversation->getUser()) <= $groupConversation->getMaxMemberCount()) {
                $user = $this->userRepository->getUserById($userId);
                $groupConversation->addUser($user);
                $groupConversation = $this->groupConversationRepository->store($groupConversation);
            }
        }

        return $groupConversation;
    }

    public function findGroupConversation(string $groupConversationGroupName, int $userId): array
    {
        return $this->groupConversationRepository->findGroupConversationByName($groupConversationGroupName, $userId);
    }
}
