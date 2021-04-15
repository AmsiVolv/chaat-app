<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\GroupConversation;
use App\Repository\CourseRepository;
use App\Repository\CourseShedulingRepository;
use App\Repository\GroupConversationRepository;
use App\Repository\GroupMessageRepository;
use App\Repository\ReadingRepository;
use App\Repository\TeacherRepository;
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
    private CourseService $courseService;

    public function __construct(
        GroupConversationRepository $groupConversationRepository,
        UserRepository $userRepository,
        CourseService $courseService
    ) {
        $this->groupConversationRepository = $groupConversationRepository;
        $this->userRepository = $userRepository;
        $this->courseService = $courseService;
    }

    public function getByUserId(int $userId): array
    {
        $groupConversations = $this->groupConversationRepository->getByUserId($userId);

        foreach ($groupConversations as &$groupConversation) {
            $participants = $this->groupConversationRepository->getParticipantsByGroupId($groupConversation['id']);

            $groupConversation['participants'] = $participants;
        }

        return $groupConversations;
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

    /**
     * @param int $groupConversationId
     * @return array
     * @throws Throwable
     */
    public function getCourseInfo(int $groupConversationId): array
    {
        $data = ['groupId' => $groupConversationId];
        $groupConversation = $this->groupConversationRepository->getById($groupConversationId);

        if ($groupConversation) {
            $courseId = $groupConversation->getCourse()->getId();
            $data['courseInfo'] = $this->courseService->getCourseInfoByCourseId($courseId);
        }

        return $data;
    }
}
