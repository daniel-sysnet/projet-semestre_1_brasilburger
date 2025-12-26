<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zone>
 */
class ZoneRepository extends ServiceEntityRepository implements ZoneRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['nom' => 'ASC']);
    }

    public function save(Zone $zone): void
    {
        $em = $this->getEntityManager();
        $em->persist($zone);
        $em->flush();
    }

    public function delete(Zone $zone): void
    {
        $em = $this->getEntityManager();
        $em->remove($zone);
        $em->flush();
    }
}