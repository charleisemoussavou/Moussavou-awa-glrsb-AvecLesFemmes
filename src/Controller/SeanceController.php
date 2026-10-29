<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Service\SeanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/seances')]
final class SeanceController extends AbstractController
{
    public function __construct(private readonly SeanceService $seanceService)
    {
    }

    #[Route('/{id}', name: 'app_seance_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $this->seanceService->detail($seance),
        ]);
    }
}