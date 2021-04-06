<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Message;
use App\Factory\MessageFactory;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class MessageService
 * @package App\Services
 */
class MessageService
{
    private MessageRepository $messageRepository;
    private ConversationRepository $conversationRepository;
    private UserRepository $userRepository;

    public function __construct(
        MessageRepository $messageRepository,
        ConversationRepository $conversationRepository,
        UserRepository $userRepository
    ) {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
        $this->userRepository = $userRepository;
    }


    public function findMessageByConversationId(int $conversationId): array
    {
        return $this->messageRepository->findMessageByConversationId($conversationId);
    }

    /**
     * @param Request $request
     * @param int $senderId
     * @param int $recipientUser
     * @param int $conversationId
     * @return Message
     * @throws Throwable
     */
    public function createMessageFromRequest(
        Request $request,
        int $senderId,
        int $recipientUser,
        int $conversationId
    ): Message {
        $content = $request->get('content', null);
        $sender = $this->userRepository->getUserById($senderId);
        $recipientUser = $this->userRepository->getUserById($recipientUser);
        $conversation = $this->conversationRepository->getById($conversationId);

        $message = MessageFactory::createMessage(
            $content,
            $sender,
            $recipientUser
        );

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->messageRepository->store($message);
        $this->conversationRepository->store($conversation);

        return $message;
    }
}
