<?php

namespace App\Service;

use App\Dto\CampagneDto;
use App\Dto\ExerciceDto;
use App\Dto\GroupementDetailDto;
use App\Dto\GroupementDto;
use App\Entity\Campagne;
use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Enum\StatutExercice;
use App\Repository\CampagneRepository;
use App\Repository\GroupementRepository;

class GroupementService
{
    public function __construct(
        private readonly GroupementRepository $groupementRepository,
        private readonly CampagneRepository $campagneRepository,
        private readonly Formatteur $formatteur,
    ) {
    }

    /**
     * @return GroupementDto[]
     */
    public function lister(): array
    {
        $groupements = $this->groupementRepository->findBy([], ['nom' => 'ASC']);

        return array_map(fn (Groupement $g) => $this->versGroupementDto($g), $groupements);
    }

    public function detail(Groupement $groupement): GroupementDetailDto
    {
        $campagnes = $this->campagneRepository->findByGroupement($groupement);

        return new GroupementDetailDto(
            $this->versGroupementDto($groupement),
            array_map(fn (Campagne $c) => $this->versCampagneDto($c), $campagnes),
        );
    }

    private function versGroupementDto(Groupement $groupement): GroupementDto
    {
        $exercice = $this->trouverExerciceEnCours($groupement);

        return new GroupementDto(
            $groupement->getId(),
            $groupement->getNom(),
            $groupement->getTelephone(),
            $groupement->getAdresse() ?? 'Non renseignée',
            $exercice ? $this->versExerciceDto($exercice) : null,
        );
    }

    private function versExerciceDto(Exercice $exercice): ExerciceDto
    {
        return new ExerciceDto(
            $exercice->getId(),
            $this->formatteur->date($exercice->getDateDebut()),
            $this->formatteur->date($exercice->getDateFin()),
            $this->formatteur->date($exercice->getDateLimitePret()),
            $this->formatteur->date($exercice->getDateRetribution()),
            $exercice->getStatut()->label(),
        );
    }

    private function versCampagneDto(Campagne $campagne): CampagneDto
    {
        $exercice = $campagne->getExercice();

        return new CampagneDto(
            $campagne->getId(),
            $campagne->getNom(),
            $campagne->getTypeCampagne()->getNom(),
            $campagne->getPeriodicite(),
            $campagne->getJourRencontre(),
            $this->formatteur->montant($campagne->getMontantFixe()),
            $this->formatteur->date($exercice->getDateDebut()) . ' au ' . $this->formatteur->date($exercice->getDateFin()),
            $exercice->getStatut()->label(),
        );
    }

    private function trouverExerciceEnCours(Groupement $groupement): ?Exercice
    {
        foreach ($groupement->getExercices() as $exercice) {
            if ($exercice->getStatut() === StatutExercice::EN_COURS) {
                return $exercice;
            }
        }

        return null;
    }
}