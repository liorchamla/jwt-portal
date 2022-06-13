<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['application:read', 'account:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['application:read', 'account:read'])]
    #[Constraints\NotBlank()]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['application:read', 'account:read'])]
    #[Constraints\NotBlank()]
    private $description;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    #[ORM\OneToMany(mappedBy: 'application', targetEntity: ProxyRoute::class, orphanRemoval: true, cascade: ["persist"])]
    #[Groups(['application:read', 'account:read'])]
    private $routes;

    public function getSerializedRoutes()
    {
        return $this->routes->toArray();
    }

    #[ORM\OneToMany(mappedBy: 'application', targetEntity: Account::class, orphanRemoval: true)]
    #[Groups('application:read')]
    private $accounts;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->accounts = new ArrayCollection();
    }

    public function hasOwner()
    {
        return !empty($this->owner);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, ProxyRoute>
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function addRoute(ProxyRoute $route): self
    {
        if (!$this->routes->contains($route)) {
            $this->routes[] = $route;
            $route->setApplication($this);
        }

        return $this;
    }

    public function removeRoute(ProxyRoute $route): self
    {
        if ($this->routes->removeElement($route)) {
            // set the owning side to null (unless already changed)
            if ($route->getApplication() === $this) {
                $route->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setApplication($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getApplication() === $this) {
                $account->setApplication(null);
            }
        }

        return $this;
    }
}
