<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserDto
 * @package App\DTO
 */
class UserDto implements UserInterface
{
    private string $username;
    private string $email;
    private string $password;
    private string $salt = 'neco';
    private bool $isVerified;
    private array $roles = [];

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        
        return $this;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
