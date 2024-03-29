<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\UserDto;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

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

    public function findByUserId(int $id): User
    {
        return $this->userRepository->find(['id' => 2]);
    }

    /**
     * @param string $email
     * @return User|null
     * @throws Throwable
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->getUserByEmail($email);
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

    /**
     * @param Request $request
     * @return array
     */
    public function getUsernameFromRequest(Request $request, int $userId): array
    {
        $username = $request->get('username');

        return $this->userRepository->findUsersByUsername($username, $userId);
    }
}
