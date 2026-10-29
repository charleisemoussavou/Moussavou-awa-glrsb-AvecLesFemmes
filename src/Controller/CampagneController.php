<?php

namespace App\Controller;

use App\Entity\Campagne;
use App\Service\CampagneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/campagnes')]
final class CampagneController extends AbstractController
{
    public function __construct(private readonly CampagneService $campagneService)
    {
    }

    #[Route('/{id}', name: 'app_campagne_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Campagne $campagne): Response
    {
        return $this->render('campagne/show.html.twig', [
            'campagne' => $this->campagneService->detail($campagne),
        ]);
    }
}