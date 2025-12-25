<?php

namespace App\Repository;

use App\Entity\Livreur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livreur>
 */
class LivreurRepository extends ServiceEntityRepository implements LivreurRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livreur::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['nom' => 'ASC']);
    }

    public function save(Livreur $livreur): void
    {
        $em = $this->getEntityManager();
        $em->persist($livreur);
        $em->flush();
    }

    public function delete(Livreur $livreur): void
    {
        $em = $this->getEntityManager();
        $em->remove($livreur);
        $em->flush();
    }
}