<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\GroupMessageDTO;
use App\Entity\GroupMessage;

/**
 * Class GroupMessageAssembler
 * @package App\Assembler
 */
class GroupMessageAssembler
{
    public function toDto(GroupMessage $groupMessage): GroupMessageDTO
    {
        return (new GroupMessageDTO())
            ->setId($groupMessage->getId())
            ->setGroupId($groupMessage->getGroupConversation()->getId())
            ->setContent($groupMessage->getMessageContent())
            ->setAuthorName($groupMessage->getUser()->getUsername())
            ->setIconColor($groupMessage->getUser()->getIconColor())
            ->setCreatedAt($groupMessage->getCreatedAt())
            ->setGroupName($groupMessage->getGroupConversation()->getGroupName())
            ->setGroupIconColor($groupMessage->getGroupConversation()->getGroupColor());
    }
}
