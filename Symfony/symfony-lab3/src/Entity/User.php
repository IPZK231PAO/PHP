<?php
// src/Entity/User.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $e): self { $this->email = $e; return $this; }
    public function getUserIdentifier(): string { return $this->email; }
   
    public function getRoles(): array
    {
        return $this->roles;
    }
    public function setRoles(array $roles): self
{
    if (count($roles) > 1) {
        throw new \InvalidArgumentException("Користувач може мати лише одну роль");
    }
    
    // Якщо роль менеджера, додамо роль клієнта
    if (in_array('ROLE_MANAGER', $roles)) {
        $roles[] = 'ROLE_CLIENT';
    }

    // Якщо роль адміністратора, додамо роль клієнта та менеджера
    if (in_array('ROLE_ADMIN', $roles)) {
        $roles[] = 'ROLE_MANAGER';
        $roles[] = 'ROLE_CLIENT';
    }

    $this->roles = $roles;
    return $this;
}


    public function getPassword(): string { return $this->password; }
    public function setPassword(string $p): self { $this->password = $p; return $this; }

    public function eraseCredentials(): void {}
}
