<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use App\Repository\ZoneRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/zones')]
final class ZoneController extends AbstractController
{
    public function __construct(
        private ZoneRepositoryInterface $zoneRepository
    ) {}

    #[Route('/', name: 'zone_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('zone/index.html.twig', [
            'zones' => $this->zoneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'zone_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->zoneRepository->save($zone);
            return $this->redirectToRoute('zone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('zone/new.html.twig', [
            'zone' => $zone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'zone_show', methods: ['GET'])]
    public function show(Zone $zone): Response
    {
        return $this->render('zone/show.html.twig', [
            'zone' => $zone,
        ]);
    }

    #[Route('/{id}/edit', name: 'zone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Zone $zone): Response
    {
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->zoneRepository->save($zone);
            return $this->redirectToRoute('zone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('zone/edit.html.twig', [
            'zone' => $zone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'zone_delete', methods: ['POST'])]
    public function delete(Request $request, Zone $zone): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zone->getId(), $request->getPayload()->getString('_token'))) {
            $this->zoneRepository->delete($zone);
        }

        return $this->redirectToRoute('zone_index', [], Response::HTTP_SEE_OTHER);
    }
}