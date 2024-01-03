<?php

namespace App\Entity;

use App\Repository\ReleveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReleveRepository::class)]
class Releve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'La date ne peut pas être vide.')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le relevé brut ne peut pas être vide.')]
    #[Assert\Regex(pattern: '/^\d+(\/\d+){8}$/', message: 'Le relevé brut doit être au format correct (ex: 3/3/3/9/6/6/1/9/4).')]
    private ?string $releveBrut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReleveBrut(): ?string
    {
        return $this->releveBrut;
    }

    public function setReleveBrut(string $releveBrut): static
    {
        $this->releveBrut = $releveBrut;

        return $this;
    }
}
