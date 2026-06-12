<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/scores/add',
            description: 'Ajout d\'un score',
            security: "is_granted('ROLE_USER')",
        ),
        new GetCollection(
            uriTemplate: '/scores',
            description: 'Récupération des scores',
            security: "is_granted('ROLE_USER')",
        ),
    ],
    normalizationContext: ['groups' => ['score:read']],
    denormalizationContext: ['groups' => ['score:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['points' => 'DESC', 'createdAt' => 'DESC'])]
#[ApiFilter(SearchFilter::class, properties: ['user.id' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['points'])]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['score:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['score:read', 'score:write'])]
    #[Assert\NotBlank(message: 'Les points sont obligatoires')]
    #[Assert\Positive(message: 'Les points doivent être positifs')]
    private ?int $points = null;

    #[ORM\Column]
    #[Groups(['score:read', 'score:write'])]
    #[Assert\NotBlank(message: 'La durée est obligatoire')]
    #[Assert\Positive(message: 'La durée doit être positive')]
    private ?int $duration = null;
    
    #[ORM\Column]
    #[Groups(['score:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['score:read'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}