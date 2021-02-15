<?php
declare(strict_types=1);

namespace App\Assembler;

use App\DTO\UserDto;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserDtoAssembler
 * @package App\Assembler
 */
class UserDtoAssembler
{
    private UserDto $userDto;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserDto $userDto, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userDto = $userDto;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function toUserDtoFromRequest(Request $request): UserDto
    {
        $request = $request->get('registration_form');

        return (new UserDto())
            ->setEmail($request[RegistrationFormType::EMAIL_FIELD])
            ->setUsername($request[RegistrationFormType::USERNAME_FIELD])
            ->setPassword($this->encodePassword($request[RegistrationFormType::PLAIN_PASSWORD_FIELD]));
    }

    private function encodePassword(string $plainPassword): string
    {
        return $this->passwordEncoder->encodePassword(
            new UserDto(),
            $plainPassword
        );
    }
}
