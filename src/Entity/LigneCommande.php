<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ligne_commande")]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Commande $commande;

    #[ORM\ManyToOne]
    private ?Burger $burger = null;

    #[ORM\ManyToOne]
    private ?Menu $menu = null;

    #[ORM\ManyToOne]
    private ?Complement $complement = null;

    #[ORM\Column]
    private int $quantite;

    public function getId(): ?int { return $this->id; }
}
