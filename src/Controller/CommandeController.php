<?php

namespace App\Controller;

use App\Entity\Commande;
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

    #[Route('/{id}', name: 'commandes_show')]
    public function show(Commande $commande): Response
    {
        return $this->render('commandes/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/valider', name: 'commandes_valider', methods: ['POST'])]
    public function valider(Commande $commande, EntityManagerInterface $em): Response
    {
        if ($commande->getEtat() === 'En cours') {
            $commande->setEtat('Validee');
            $em->flush();
            $this->addFlash('success', 'Commande validée.');
        }

        return $this->redirectToRoute('commandes_index');
    }

    #[Route('/{id}/annuler', name: 'commandes_annuler', methods: ['POST'])]
    public function annuler(Commande $commande, EntityManagerInterface $em): Response
    {
        if (in_array($commande->getEtat(), ['En cours', 'Validee'])) {
            $commande->setEtat('Annulee');
            $em->flush();
            $this->addFlash('success', 'Commande annulée.');
        }

        return $this->redirectToRoute('commandes_index');
    }
}