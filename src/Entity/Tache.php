<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie est obligatoire.")]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ['a_faire', 'en_cours', 'termine'], message: "Le statut est invalide.")]
    private ?string $statut = 'a_faire';

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ['basse', 'moyenne', 'haute'], message: "La priorité est invalide.")]
    private ?string $priorite = 'moyenne';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date limite est obligatoire.")]
    private ?\DateTimeInterface $maxDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->statut = 'a_faire';
        $this->priorite = 'moyenne';
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getPriorite(): ?string
    {
        return $this->priorite;
    }

    public function setPriorite(string $priorite): static
    {
        $this->priorite = $priorite;
        return $this;
    }

    public function getMaxDate(): ?\DateTimeInterface
    {
        return $this->maxDate;
    }

    public function setMaxDate(\DateTimeInterface $maxDate): static
    {
        $this->maxDate = $maxDate;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
