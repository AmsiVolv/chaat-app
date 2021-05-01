<?php
declare(strict_types=1);

namespace App\Services;

use App\Factory\ChatbotMessageFactory;
use App\Repository\ChatbotMessagesRepository;
use App\Repository\UserRepository;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class ChatbotMessagesService
 * @package App\Services
 */
class ChatbotMessagesService
{
    private ChatbotMessagesRepository $chatbotMessagesRepository;
    private UserRepository $userRepository;

    public function __construct(
        ChatbotMessagesRepository $chatbotMessagesRepository,
        UserRepository $userRepository
    ) {
        $this->chatbotMessagesRepository = $chatbotMessagesRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param stdClass $requestData
     * @param int $userId
     * @throws Throwable
     */
    public function saveFromRequest(stdClass $requestData, int $userId): void
    {
        $messagesArray = $requestData->chatbotMessages;
        $user = $this->userRepository->getUserById($userId);

        if ($user) {
            foreach ($messagesArray as $message) {
                if (!$this->chatbotMessagesRepository->findByExternalIdAndUserId($message['id'], $userId)) {
                    $chatBotMessage = ChatbotMessageFactory::create($message, $user);
                    $this->chatbotMessagesRepository->store($chatBotMessage);
                }
            }
        }
    }
}
