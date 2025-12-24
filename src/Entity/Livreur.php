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
}
