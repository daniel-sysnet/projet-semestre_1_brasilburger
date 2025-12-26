<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository implements CommandeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Trouver les commandes par état
     */
    public function findByEtat(string $etat): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat = :etat')
            ->setParameter('etat', $etat)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les commandes du jour
     */
    public function findCommandesDuJour(): array
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->createQueryBuilder('c')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compter les commandes par état pour le jour
     */
    public function countByEtatDuJour(string $etat): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.etat = :etat')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('etat', $etat)
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Calculer le CA du jour
     */
    public function getChiffreAffairesDuJour(): float
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        $result = $this->createQueryBuilder('c')
            ->select('SUM(p.montant) as total')
            ->join('c.paiement', 'p')  // Assumant une relation OneToOne
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?? 0.0;
    }

    /**
     * Trouver les commandes terminées à livrer, groupées par zone
     */
    public function findCommandesALivrerParZone(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat = :etat')
            ->andWhere('c.type = :type')
            ->andWhere('c.livreur IS NULL')  // Non encore assignées
            ->setParameter('etat', 'Terminée')
            ->setParameter('type', 'À livrer')
            ->orderBy('c.zone', 'ASC')
            ->addOrderBy('c.dateCommande', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les commandes avec filtres
     */
    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.client', 'cl')
            ->leftJoin('c.ligneCommandes', 'lc')
            ->leftJoin('lc.burger', 'b')
            ->leftJoin('lc.menu', 'm');

        if (!empty($filters['etat'])) {
            $qb->andWhere('c.etat = :etat')
               ->setParameter('etat', $filters['etat']);
        }

        if (!empty($filters['date'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $filters['date']);
            if ($date) {
                $nextDay = clone $date;
                $nextDay->modify('+1 day');
                $qb->andWhere('c.dateCommande >= :date_start')
                   ->andWhere('c.dateCommande < :date_end')
                   ->setParameter('date_start', $date)
                   ->setParameter('date_end', $nextDay);
            }
        }

        if (!empty($filters['client'])) {
            $qb->andWhere('cl.nom LIKE :client')
               ->setParameter('client', '%' . $filters['client'] . '%');
        }

        if (!empty($filters['burger'])) {
            $qb->andWhere('b.nom LIKE :burger')
               ->setParameter('burger', '%' . $filters['burger'] . '%');
        }

        if (!empty($filters['menu'])) {
            $qb->andWhere('m.nom LIKE :menu')
               ->setParameter('menu', '%' . $filters['menu'] . '%');
        }

        return $qb->orderBy('c.dateCommande', 'DESC')
                  ->getQuery()
                  ->getResult();
    }
}