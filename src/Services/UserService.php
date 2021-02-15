<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\UserDto;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserDto $userDto
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function storeFromUserDto(UserDto $userDto): User
    {
        $user = UserFactory::createUserFromDto($userDto);

        return $this->userRepository->store($user);
    }
}
