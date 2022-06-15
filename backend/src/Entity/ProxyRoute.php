<?php

namespace App\Entity;


use App\Repository\ProxyRouteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProxyRouteRepository::class)]
#[HasLifecycleCallbacks]
class ProxyRoute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['application:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['application:read'])]
    private $pattern;

    #[ORM\ManyToOne(targetEntity: Application::class, inversedBy: 'routes')]
    #[ORM\JoinColumn(nullable: false)]
    private $application;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['application:read'])]
    private $clientPattern;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['application:read'])]
    private $isProtected = false;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['application:read'])]
    private $method = "GET";

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['application:read'])]
    private $description = "";

    #[PrePersist]
    #[PreUpdate]
    public function lifeCycleCallbacks()
    {
        if (!$this->clientPattern) {
            $this->clientPattern = $this->pattern;
        }
    }

    public  function getClientPatternParameters()
    {
        $results = [];

        preg_match_all("/{(.*?)}/", $this->clientPattern, $results);

        return $results[1];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;

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

    public function getClientPattern(): ?string
    {
        return $this->clientPattern;
    }

    public function setClientPattern(string $clientPattern): self
    {
        $this->clientPattern = $clientPattern;

        return $this;
    }

    public function isProtected(): ?bool
    {
        return $this->isProtected;
    }

    public function setIsProtected(bool $isProtected): self
    {
        $this->isProtected = $isProtected;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

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
}
