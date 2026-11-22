<?php

namespace App\Service;

use App\Dto\CampagneDto;
use App\Dto\ExerciceDto;
use App\Dto\FiltreGroupementDto;
use App\Dto\GroupementDetailDto;
use App\Dto\GroupementDto;
use App\Dto\PageGroupementDto;
use App\Entity\Campagne;
use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Repository\CampagneRepository;
use App\Repository\GroupementRepository;

class GroupementService
{
    private const PAR_PAGE = 2;

    public function __construct(
        private readonly GroupementRepository $groupementRepository,
        private readonly CampagneRepository $campagneRepository,
        private readonly Formatteur $formatteur,
    ) {
    }

    public function lister(FiltreGroupementDto $filtre, int $page): PageGroupementDto
    {
        $recherche = trim((string) $filtre->recherche);
        $recherche = $recherche === '' ? null : $recherche;
        $page = max(1, $page);

        $resultats = $this->groupementRepository->findPageAndExerciceEnCours($recherche, $page, self::PAR_PAGE);
        $total = count($resultats);

        return new PageGroupementDto(
            array_map(fn (Groupement $g) => $this->versGroupementDto($g), iterator_to_array($resultats)),
            $page,
            max(1, (int) ceil($total / self::PAR_PAGE)),
            $total,
            $recherche ?? '',
        );
    }

    public function detail(int $id): ?GroupementDetailDto
    {
        $groupement = $this->groupementRepository->findOneAndExerciceEnCours($id);

        if ($groupement === null) {
            return null;
        }

        $campagnes = $this->campagneRepository->findByGroupement($groupement);

        return new GroupementDetailDto(
            $this->versGroupementDto($groupement),
            array_map(fn (Campagne $c) => $this->versCampagneDto($c), $campagnes),
        );
    }

    private function versGroupementDto(Groupement $groupement): GroupementDto
    {
        $exercice = $groupement->getExercices()->first() ?: null;

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
}