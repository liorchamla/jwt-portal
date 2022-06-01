<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["account:read", "application:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["account:read", "application:read"])]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private $password;

    #[ORM\ManyToOne(targetEntity: Application::class, inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["account:read"])]
    private $application;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }
}
