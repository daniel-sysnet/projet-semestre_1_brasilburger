<?php

namespace App\Repository;

interface StatistiquesRepositoryInterface
{
    public function getNombreTotalCommandes(): int;
    public function getChiffreAffairesTotal(): float;
    public function getCommandesParEtat(): array;
    public function getRevenusParJour(\DateTime $date): float;
    public function getTopProduits(): array;
    public function getCommandesEnCoursDuJour(): int;
    public function getCommandesValideesDuJour(): int;
    public function getCommandesAnnuleesDuJour(): int;
}