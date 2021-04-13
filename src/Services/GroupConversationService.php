<?php
declare(strict_types=1);

namespace App\Services;

use App\Repository\GroupConversationRepository;
use App\Repository\GroupMessageRepository;

/**
 * Class GroupConversationService
 * @package App\Services
 */
class GroupConversationService
{
    private GroupConversationRepository $groupConversationRepository;

    public function __construct(GroupConversationRepository $groupConversationRepository)
    {
        $this->groupConversationRepository = $groupConversationRepository;
    }

    public function getByUserId(int $userId): array
    {
        return $this->groupConversationRepository->getByUserId($userId);
    }

    public function getMembersByGroupId(int $getGroupId): array
    {
        return $this->groupConversationRepository->getMembersByGroupId($getGroupId);
    }
}
