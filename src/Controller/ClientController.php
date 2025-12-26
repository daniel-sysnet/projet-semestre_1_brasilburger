<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients')]
final class ClientController extends AbstractController
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository
    ) {}

    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $this->clientRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }
}