<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const STATUS_WAIT = 1;
    public const STATUS_ACTIVE = 5;
    public const STATUS_BLOCKED = 6;

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 16)]
    private string $role;

    #[ORM\Column(type: 'smallint')]
    private int $status;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    public function __construct(string $email, string $username, string $password)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->status = self::STATUS_ACTIVE;
        $this->role = self::ROLE_USER;
    }

    #[Pure]
    public static function create(string $username, string $email, string $password): self
    {
        return new self($email, $username, $password);
    }

    public function changeRole(string $role): void
    {
        if ($this->role === $role) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles[] = $this->role;

        return $roles;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
