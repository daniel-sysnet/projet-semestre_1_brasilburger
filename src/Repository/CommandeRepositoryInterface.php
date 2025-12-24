<?php

namespace App\Repository;

use App\Entity\Commande;

interface CommandeRepositoryInterface
{
    public function findByEtat(string $etat): array;
    public function findCommandesDuJour(): array;
    public function countByEtatDuJour(string $etat): int;
    public function getChiffreAffairesDuJour(): float;
}