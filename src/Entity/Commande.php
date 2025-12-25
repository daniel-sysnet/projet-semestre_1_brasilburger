<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\CommandeRepository::class)]
#[ORM\Table(name: "commande")]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $dateCommande;

    #[ORM\Column(length: 50)]
    private string $etat;

    #[ORM\Column(length: 50)]
    private string $type;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Zone $zone = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Livreur $livreur = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneCommande::class)]
    private Collection $ligneCommandes;

    #[ORM\OneToOne(mappedBy: 'commande', targetEntity: Paiement::class)]
    private ?Paiement $paiement = null;

    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getClient(): Client { return $this->client; }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getDateCommande(): \DateTimeInterface { return $this->dateCommande; }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getEtat(): string { return $this->etat; }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getType(): string { return $this->type; }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getZone(): ?Zone { return $this->zone; }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;
        return $this;
    }

    public function getLivreur(): ?Livreur { return $this->livreur; }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }

    public function getPaiement(): ?Paiement { return $this->paiement; }

    public function setPaiement(?Paiement $paiement): self
    {
        $this->paiement = $paiement;
        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): static
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes->add($ligneCommande);
            $ligneCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): static
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getCommande() === $this) {
                $ligneCommande->setCommande(null);
            }
        }

        return $this;
    }
}
