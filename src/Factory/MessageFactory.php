<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Message;
use App\Entity\User;

/**
 * Class MessageFactory
 * @package App\Factory
 */
class MessageFactory
{
    public static function createMessage(
        string $content,
        User $user,
        User $recipientUser,
    ): Message {
        return (new Message())
            ->setContent($content)
            ->setUser($user)
            ->setRecipientUser($recipientUser);
    }
}
