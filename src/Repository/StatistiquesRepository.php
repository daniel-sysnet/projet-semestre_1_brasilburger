<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class StatistiquesRepository implements StatistiquesRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getNombreTotalCommandes(): int
    {
        return $this->entityManager->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from('App\Entity\Commande', 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getChiffreAffairesTotal(): float
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('SUM(p.montant)')
            ->from('App\Entity\Paiement', 'p')
            ->join('p.commande', 'c')
            ->where('c.etat = :etat')
            ->setParameter('etat', 'validee');

        return (float) $qb->getQuery()->getSingleScalarResult() ?? 0.0;
    }

    public function getCommandesParEtat(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c.etat, COUNT(c.id) as nombre')
            ->from('App\Entity\Commande', 'c')
            ->groupBy('c.etat');

        $result = $qb->getQuery()->getResult();
        $stats = [];
        foreach ($result as $row) {
            $stats[$row['etat']] = (int) $row['nombre'];
        }
        return $stats;
    }

    public function getRevenusParJour(\DateTime $date): float
    {
        $start = new \DateTime($date->format('Y-m-d') . ' 00:00:00');
        $end = new \DateTime($date->format('Y-m-d') . ' 23:59:59');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('SUM(p.montant)')
            ->from('App\Entity\Paiement', 'p')
            ->join('p.commande', 'c')
            ->where('c.dateCommande >= :start')
            ->andWhere('c.dateCommande <= :end')
            ->andWhere('c.etat = :etat')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('etat', 'validee');

        return (float) $qb->getQuery()->getSingleScalarResult() ?? 0.0;
    }

    public function getTopProduits(): array
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        // Top burgers du jour
        $qbBurgers = $this->entityManager->createQueryBuilder();
        $qbBurgers->select('b.nom, SUM(lc.quantite) as total')
            ->from('App\Entity\LigneCommande', 'lc')
            ->join('lc.burger', 'b')
            ->join('lc.commande', 'c')
            ->where('c.etat = :etat')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('etat', 'validee')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->groupBy('b.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(5);

        $burgers = $qbBurgers->getQuery()->getResult();

        // Top menus du jour
        $qbMenus = $this->entityManager->createQueryBuilder();
        $qbMenus->select('m.nom, SUM(lc.quantite) as total')
            ->from('App\Entity\LigneCommande', 'lc')
            ->join('lc.menu', 'm')
            ->join('lc.commande', 'c')
            ->where('c.etat = :etat')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('etat', 'validee')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->groupBy('m.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(5);

        $menus = $qbMenus->getQuery()->getResult();

        return [
            'burgers' => $burgers,
            'menus' => $menus,
        ];
    }

    public function getCommandesEnCoursDuJour(): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->entityManager->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from('App\Entity\Commande', 'c')
            ->andWhere('c.etat = :etat')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('etat', 'En cours')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCommandesValideesDuJour(): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->entityManager->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from('App\Entity\Commande', 'c')
            ->andWhere('c.etat = :etat')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('etat', 'Validee')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCommandesAnnuleesDuJour(): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->entityManager->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from('App\Entity\Commande', 'c')
            ->andWhere('c.etat = :etat')
            ->andWhere('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('etat', 'Annulee')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getSingleScalarResult();
    }
}