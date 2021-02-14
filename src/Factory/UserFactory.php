<?php
declare(strict_types=1);

namespace App\Factory;

use App\DTO\UserDto;
use App\Entity\User;

/**
 * Class UserFactory
 * @package App\Factory
 */
class UserFactory
{
    public static function createUserFromDto(UserDto $userDto): User
    {
        return new User(
            $userDto->getUsername(),
            $userDto->getEmail(),
            $userDto->getPassword(),
        );
    }
}