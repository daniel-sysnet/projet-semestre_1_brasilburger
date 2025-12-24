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

    #[ORM\ManyToOne(inversedBy: 'ligneCommandes')]
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

    public function getCommande(): Commande { return $this->commande; }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }

    public function getBurger(): ?Burger { return $this->burger; }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;
        return $this;
    }

    public function getMenu(): ?Menu { return $this->menu; }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;
        return $this;
    }

    public function getComplement(): ?Complement { return $this->complement; }

    public function setComplement(?Complement $complement): self
    {
        $this->complement = $complement;
        return $this;
    }

    public function getQuantite(): int { return $this->quantite; }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}
