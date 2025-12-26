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
    public function index(Request $request): Response
    {
        $filters = [
            'etat' => $request->query->get('etat'),
            'date' => $request->query->get('date'),
            'client' => $request->query->get('client'),
            'burger' => $request->query->get('burger'),
            'menu' => $request->query->get('menu'),
        ];

        $commandes = $this->commandeRepository->findByFilters(array_filter($filters));

        return $this->render('commandes/index.html.twig', [
            'commandes' => $commandes,
            'filters' => $filters,
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

    #[Route('/{id}/payer', name: 'commandes_payer', methods: ['POST'])]
    public function payer(Request $request, Commande $commande, EntityManagerInterface $em): Response
    {
        if ($commande->getPaiement()) {
            $this->addFlash('error', 'Cette commande est déjà payée.');
            return $this->redirectToRoute('commandes_index');
        }

        if ($commande->getEtat() !== 'Validée') {
            $this->addFlash('error', 'Seules les commandes validées peuvent être payées.');
            return $this->redirectToRoute('commandes_index');
        }

        $methode = $request->request->get('methode');
        if (!in_array($methode, ['Wave', 'Orange Money'])) {
            $this->addFlash('error', 'Méthode de paiement invalide.');
            return $this->redirectToRoute('commandes_index');
        }

        $paiement = new Paiement();
        $paiement->setCommande($commande);
        $paiement->setDatePaiement(new \DateTime());
        $paiement->setMontant($commande->getTotal());
        $paiement->setMethode($methode);

        $commande->setEtat('Terminée');  // Une fois payée, passe à Terminée

        $em->persist($paiement);
        $em->flush();

        $this->addFlash('success', 'Paiement enregistré avec succès.');
        return $this->redirectToRoute('commandes_index');
    }

    #[Route('/{id}/valider', name: 'commandes_valider', methods: ['POST'])]
    public function valider(Commande $commande, EntityManagerInterface $em): Response
    {
        if ($commande->getEtat() !== 'En attente') {
            $this->addFlash('error', 'Seules les commandes en attente peuvent être validées.');
            return $this->redirectToRoute('commandes_index');
        }

        $commande->setEtat('Validée');
        $em->flush();

        $this->addFlash('success', 'Commande validée avec succès.');
        return $this->redirectToRoute('commandes_index');
    }

    #[Route('/{id}/annuler', name: 'commandes_annuler', methods: ['POST'])]
    public function annuler(Commande $commande, EntityManagerInterface $em): Response
    {
        if (in_array($commande->getEtat(), ['Terminée', 'Annulée'])) {
            $this->addFlash('error', 'Cette commande ne peut pas être annulée.');
            return $this->redirectToRoute('commandes_index');
        }

        $commande->setEtat('Annulée');
        $em->flush();

        $this->addFlash('success', 'Commande annulée avec succès.');
        return $this->redirectToRoute('commandes_index');
    }
}