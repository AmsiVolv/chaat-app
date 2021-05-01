<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\ChatbotMessages;
use App\Entity\User;
use JetBrains\PhpStorm\Pure;

/**
 * Class ChatbotMessageFactory
 * @package App\Factory
 */
class ChatbotMessageFactory
{
    #[Pure] public static function create(array $message, User $user): ChatbotMessages
    {
        return new ChatbotMessages (
            $message['id'],
            $message['message'],
            $message['type'],
            $user
        );
    }
}
