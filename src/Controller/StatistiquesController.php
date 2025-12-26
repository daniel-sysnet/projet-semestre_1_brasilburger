<?php

namespace App\Controller;

use App\Repository\StatistiquesRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StatistiquesController extends AbstractController
{
    private StatistiquesRepositoryInterface $statistiquesRepository;

    public function __construct(StatistiquesRepositoryInterface $statistiquesRepository)
    {
        $this->statistiquesRepository = $statistiquesRepository;
    }

    #[Route('/statistiques', name: 'app_statistics')]
    public function index(): Response
    {
        $nombreTotalCommandes = $this->statistiquesRepository->getNombreTotalCommandes();
        $chiffreAffairesTotal = $this->statistiquesRepository->getChiffreAffairesTotal();
        $commandesParEtat = $this->statistiquesRepository->getCommandesParEtat();
        $revenusAujourdhui = $this->statistiquesRepository->getRevenusParJour(new \DateTime());
        $topProduits = $this->statistiquesRepository->getTopProduits();
        $commandesEnCoursDuJour = $this->statistiquesRepository->getCommandesEnCoursDuJour();
        $commandesValideesDuJour = $this->statistiquesRepository->getCommandesValideesDuJour();
        $commandesAnnuleesDuJour = $this->statistiquesRepository->getCommandesAnnuleesDuJour();

        return $this->render('statistiques/index.html.twig', [
            'nombreTotalCommandes' => $nombreTotalCommandes,
            'chiffreAffairesTotal' => $chiffreAffairesTotal,
            'commandesParEtat' => $commandesParEtat,
            'revenusAujourdhui' => $revenusAujourdhui,
            'topProduits' => $topProduits,
            'commandesEnCoursDuJour' => $commandesEnCoursDuJour,
            'commandesValideesDuJour' => $commandesValideesDuJour,
            'commandesAnnuleesDuJour' => $commandesAnnuleesDuJour,
        ]);
    }
}
