<?php

namespace App\Repository;

use App\Entity\Livreur;

interface LivreurRepositoryInterface
{
    public function findAll(): array;
    public function save(Livreur $livreur): void;
    public function delete(Livreur $livreur): void;
}