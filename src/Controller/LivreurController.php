<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Form\LivreurType;
use App\Repository\LivreurRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livreurs')]
final class LivreurController extends AbstractController
{
    public function __construct(
        private LivreurRepositoryInterface $livreurRepository
    ) {}

    #[Route('/', name: 'livreur_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('livreur/index.html.twig', [
            'livreurs' => $this->livreurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'livreur_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->livreurRepository->save($livreur);
            return $this->redirectToRoute('livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/new.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'livreur_show', methods: ['GET'])]
    public function show(Livreur $livreur): Response
    {
        return $this->render('livreur/show.html.twig', [
            'livreur' => $livreur,
        ]);
    }

    #[Route('/{id}/edit', name: 'livreur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livreur $livreur): Response
    {
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->livreurRepository->save($livreur);
            return $this->redirectToRoute('livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/edit.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'livreur_delete', methods: ['POST'])]
    public function delete(Request $request, Livreur $livreur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->getPayload()->getString('_token'))) {
            $this->livreurRepository->delete($livreur);
        }

        return $this->redirectToRoute('livreur_index', [], Response::HTTP_SEE_OTHER);
    }
}