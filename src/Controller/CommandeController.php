<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livreur;
use App\Entity\Zone;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commandes')]
class CommandeController extends AbstractController
{
    public function __construct(private CommandeRepository $commandeRepository, private EntityManagerInterface $em) {}

    #[Route('/', name: 'commandes_index')]
    public function index(): Response
    {
        $commandes = $this->commandeRepository->findAll();

        return $this->render('commandes/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/livraisons', name: 'commandes_livraisons')]
    public function livraisons(EntityManagerInterface $em): Response
    {
        $commandes = $this->commandeRepository->findCommandesALivrerParZone();

        // Grouper par zone
        $commandesParZone = [];
        $livreursParZone = [];
        foreach ($commandes as $commande) {
            $zoneNom = $commande->getZone() ? $commande->getZone()->getNom() : 'Sans zone';
            $commandesParZone[$zoneNom][] = $commande;
        }

        // Récupérer les livreurs par zone
        $zones = $em->getRepository(Zone::class)->findAll();
        foreach ($zones as $zone) {
            $livreursParZone[$zone->getNom()] = $em->getRepository(Livreur::class)->findBy(['zone' => $zone]);
        }

        return $this->render('commandes/livraisons.html.twig', [
            'commandesParZone' => $commandesParZone,
            'livreursParZone' => $livreursParZone,
        ]);
    }

    #[Route('/{id}', name: 'commandes_show')]
    public function show(Commande $commande): Response
    {
        return $this->render('commandes/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/assigner-livreur', name: 'commandes_assigner_livreur', methods: ['POST'])]
    public function assignerLivreur(Request $request, Commande $commande, EntityManagerInterface $em): Response
    {
        $livreurId = $request->request->get('livreur_id');
        if ($livreurId) {
            $livreur = $em->getRepository(Livreur::class)->find($livreurId);
            if ($livreur && $commande->getZone() === $livreur->getZone()) {
                $commande->setLivreur($livreur);
                $em->flush();
                $this->addFlash('success', 'Livreur assigné à la commande.');
            } else {
                $this->addFlash('error', 'Livreur invalide ou zone incompatible.');
            }
        }

        return $this->redirectToRoute('commandes_livraisons');
    }
}