<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "zone")]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column]
    private float $prix;

    public function getId(): ?int { return $this->id; }

    public function getNom(): string { return $this->nom; }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrix(): float { return $this->prix; }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }
}
