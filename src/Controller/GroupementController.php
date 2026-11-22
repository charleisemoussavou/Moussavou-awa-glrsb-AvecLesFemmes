<?php

namespace App\Controller;

use App\Dto\FiltreGroupementDto;
use App\Dto\GroupementFormDto;
use App\Form\FiltreGroupementType;
use App\Form\GroupementType;
use App\Service\GroupementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groupements')]
final class GroupementController extends AbstractController
{
    public function __construct(private readonly GroupementService $groupementService)
    {
    }

    #[Route('', name: 'app_groupement_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $filtre = new FiltreGroupementDto();
        $form = $this->createForm(FiltreGroupementType::class, $filtre);
        $form->handleRequest($request);

        return $this->render('groupement/index.html.twig', [
            'resultat' => $this->groupementService->lister($filtre, $request->query->getInt('page', 1)),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nouveau', name: 'app_groupement_nouveau', methods: ['GET', 'POST'])]
    public function nouveau(Request $request): Response
    {
        $dto = new GroupementFormDto();
        $form = $this->createForm(GroupementType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupement = $this->groupementService->creer($dto);
            $this->addFlash('succes', 'Le groupement « ' . $groupement->getNom() . ' » a été créé.');

            return $this->redirectToRoute('app_groupement_index');
        }

        return $this->render('groupement/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_groupement_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $detail = $this->groupementService->detail($id);

        if ($detail === null) {
            throw $this->createNotFoundException('Groupement introuvable.');
        }

        return $this->render('groupement/show.html.twig', [
            'detail' => $detail,
        ]);
    }
}