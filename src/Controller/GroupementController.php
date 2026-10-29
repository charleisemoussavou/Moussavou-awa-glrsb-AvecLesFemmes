<?php

namespace App\Controller;

use App\Entity\Groupement;
use App\Service\GroupementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groupements')]
final class GroupementController extends AbstractController
{
    public function __construct(private readonly GroupementService $groupementService)
    {
    }

    #[Route('', name: 'app_groupement_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('groupement/index.html.twig', [
            'groupements' => $this->groupementService->lister(),
        ]);
    }

    #[Route('/{id}', name: 'app_groupement_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Groupement $groupement): Response
    {
        return $this->render('groupement/show.html.twig', [
            'detail' => $this->groupementService->detail($groupement),
        ]);
    }
}