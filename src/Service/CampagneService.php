<?php

namespace App\Service;

use App\Dto\CampagneDetailDto;
use App\Dto\MembreCampagneDto;
use App\Dto\SeanceResumeDto;
use App\Entity\Campagne;
use App\Entity\ModeCotisation;
use App\Entity\Seance;
use App\Repository\AmendeRepository;
use App\Repository\CotisationRepository;
use App\Repository\ModeCotisationRepository;
use App\Repository\MouvementCaisseSocialeRepository;
use App\Repository\SeanceRepository;

class CampagneService
{
    public function __construct(
        private readonly ModeCotisationRepository $modeCotisationRepository,
        private readonly SeanceRepository $seanceRepository,
        private readonly CotisationRepository $cotisationRepository,
        private readonly AmendeRepository $amendeRepository,
        private readonly MouvementCaisseSocialeRepository $mouvementRepository,
        private readonly Formatteur $formatteur,
    ) {
    }

    public function detail(Campagne $campagne): CampagneDetailDto
    {
        $exercice = $campagne->getExercice();
        $groupement = $exercice->getGroupement();

        $membres = array_map(
            fn (ModeCotisation $mode) => $this->versMembreDto($mode, $campagne),
            $this->modeCotisationRepository->findByCampagne($campagne),
        );

        $totauxCotisations = $this->cotisationRepository->totauxParSeance($campagne);
        $totauxAmendes = $this->amendeRepository->totauxParSeance($campagne);
        $totauxCaisse = $this->mouvementRepository->totauxParSeance($campagne);

        $seances = array_map(
            fn (Seance $seance) => new SeanceResumeDto(
                $seance->getId(),
                $this->formatteur->date($seance->getDateSeance()),
                $this->formatteur->montant($totauxCotisations[$seance->getId()] ?? '0'),
                $this->formatteur->montant($totauxAmendes[$seance->getId()] ?? '0'),
                $this->formatteur->montant($totauxCaisse[$seance->getId()] ?? '0'),
            ),
            $this->seanceRepository->findByCampagne($campagne),
        );

        return new CampagneDetailDto(
            $campagne->getId(),
            $campagne->getNom(),
            $campagne->getTypeCampagne()->getNom(),
            $campagne->getPeriodicite(),
            $campagne->getJourRencontre(),
            $this->formatteur->montant($campagne->getMontantFixe()),
            $this->formatteur->montant($campagne->getMontantCaissePresent()),
            $this->formatteur->montant($campagne->getMontantCaisseAbsent()),
            $this->formatteur->montant($campagne->getAmendeJournaliere()),
            $this->formatteur->date($exercice->getDateDebut()) . ' au ' . $this->formatteur->date($exercice->getDateFin()),
            $groupement->getId(),
            $groupement->getNom(),
            $membres,
            $seances,
        );
    }

    private function versMembreDto(ModeCotisation $mode, Campagne $campagne): MembreCampagneDto
    {
        $membre = $mode->getMembre();

        return new MembreCampagneDto(
            $membre->getPrenom() . ' ' . $membre->getNom(),
            $membre->getTelephone(),
            $membre->getAdresse() ?? 'Non renseignée',
            $mode->isLibre()
                ? 'Montant libre'
                : 'Montant fixe (' . $this->formatteur->montant($campagne->getMontantFixe()) . ')',
        );
    }
}