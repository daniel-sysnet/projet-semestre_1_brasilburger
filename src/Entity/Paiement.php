<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "paiement")]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false, unique: true)]
    private Commande $commande;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $datePaiement;

    #[ORM\Column]
    private float $montant;

    #[ORM\Column(length: 50)]
    private string $methode;

    public function getId(): ?int { return $this->id; }
}
