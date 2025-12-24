<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CommandeRepository;

class DashboardController extends AbstractController
{
    public function __construct(private CommandeRepository $commandeRepository) {}

    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $stats = [
            'commandes_en_cours' => $this->commandeRepository->countByEtatDuJour('En cours'),
            'commandes_validees' => $this->commandeRepository->countByEtatDuJour('Validee'),
            'recettes_journalieres' => $this->commandeRepository->getChiffreAffairesDuJour(),
            'commandes_annulees' => $this->commandeRepository->countByEtatDuJour('Annulee'),
        ];

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}