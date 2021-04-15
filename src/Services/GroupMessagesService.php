<?php
declare(strict_types=1);

namespace App\Services;

use App\Assembler\GroupMessageAssembler;
use App\DTO\GroupMessageDTO;
use App\Entity\GroupMessage;
use App\Repository\GroupConversationRepository;
use App\Repository\GroupMessageRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class GroupMessagesService
 * @package App\Services
 */
class GroupMessagesService
{
    private GroupMessageRepository $groupMessageRepository;
    private GroupMessageAssembler $groupMessageAssembler;
    private UserRepository $userRepository;
    private GroupConversationRepository $groupConversationRepository;

    public function __construct(
        GroupMessageRepository $groupMessageRepository,
        GroupMessageAssembler $groupMessageAssembler,
        UserRepository $userRepository,
        GroupConversationRepository $groupConversationRepository
    ) {
        $this->groupMessageRepository = $groupMessageRepository;
        $this->groupMessageAssembler = $groupMessageAssembler;
        $this->userRepository = $userRepository;
        $this->groupConversationRepository = $groupConversationRepository;
    }

    public function findMessagesByGroupConversationId(int $conversationId): array
    {
        $data = [];
        $messages = $this->groupMessageRepository->findMessagesByGroupConversationId($conversationId);

        foreach ($messages as $message) {
            $data[] = $this->groupMessageAssembler->toDto($message);
        }

        return $data;
    }

    /**
     * @param Request $request
     * @param int $userId
     * @param int $conversationId
     * @return GroupMessageDTO
     * @throws Throwable
     */
    public function createMessageFromRequest(Request $request, int $userId, int $conversationId): GroupMessageDTO
    {
        $content = $request->get('content', null);
        $user = $this->userRepository->getUserById($userId);
        $conversation = $this->groupConversationRepository->getByConversationId($conversationId);

        $groupMessage = new GroupMessage(
            trim($content),
            $user,
            $conversation
        );

        $groupMessage = $this->groupMessageRepository->store($groupMessage);
        $conversation->setLastMessage($groupMessage)->setUpdatedAt(new DateTime());
        $this->groupConversationRepository->store($conversation);

        return $this->groupMessageAssembler->toDto($groupMessage);
    }
}
