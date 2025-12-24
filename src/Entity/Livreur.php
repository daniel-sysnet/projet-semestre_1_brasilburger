<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "livreur")]
class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column(length: 255)]
    private string $prenom;

    #[ORM\Column(length: 20)]
    private string $telephone;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "zone_id", referencedColumnName: "id", nullable: true)]
    private ?Zone $zone = null;

    public function getId(): ?int { return $this->id; }

    public function getNom(): string { return $this->nom; }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): string { return $this->prenom; }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getTelephone(): string { return $this->telephone; }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getZone(): ?Zone { return $this->zone; }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;
        return $this;
    }
}
