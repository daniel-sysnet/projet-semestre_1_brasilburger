<?php

namespace App\Repository;

use App\Entity\Zone;

interface ZoneRepositoryInterface
{
    public function findAll(): array;
    public function save(Zone $zone): void;
    public function delete(Zone $zone): void;
}