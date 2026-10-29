<?php

namespace App\Service;

use App\Dto\AmendeDto;
use App\Dto\CotisationDto;
use App\Dto\SeanceDetailDto;
use App\Entity\Amende;
use App\Entity\Cotisation;
use App\Entity\Seance;
use App\Repository\AmendeRepository;
use App\Repository\CotisationRepository;
use App\Repository\MouvementCaisseSocialeRepository;

class SeanceService
{
    public function __construct(
        private readonly CotisationRepository $cotisationRepository,
        private readonly AmendeRepository $amendeRepository,
        private readonly MouvementCaisseSocialeRepository $mouvementRepository,
        private readonly Formatteur $formatteur,
    ) {
    }

    public function detail(Seance $seance): SeanceDetailDto
    {
        $cotisations = $this->cotisationRepository->findBySeance($seance);
        $amendes = $this->amendeRepository->findBySeance($seance);
        $campagne = $seance->getCampagne();

        return new SeanceDetailDto(
            $seance->getId(),
            $this->formatteur->date($seance->getDateSeance()),
            $campagne->getId(),
            $campagne->getNom(),
            array_map(fn (Cotisation $c) => $this->versCotisationDto($c), $cotisations),
            array_map(fn (Amende $a) => $this->versAmendeDto($a), $amendes),
            $this->formatteur->montant($this->somme(array_map(fn (Cotisation $c) => $c->getMontant(), $cotisations))),
            $this->formatteur->montant($this->somme(array_map(fn (Amende $a) => $a->getMontant(), $amendes))),
            $this->formatteur->montant($this->mouvementRepository->totalPourSeance($seance)),
        );
    }

    private function versCotisationDto(Cotisation $cotisation): CotisationDto
    {
        $membre = $cotisation->getMembre();

        return new CotisationDto(
            $membre->getPrenom() . ' ' . $membre->getNom(),
            $this->formatteur->montant($cotisation->getMontant()),
            $this->formatteur->montant($cotisation->getMontantCaisse()),
            $this->formatteur->date($cotisation->getDatePaiement()),
            $cotisation->getModePaiement(),
            $this->libellePresence($cotisation->getPresence()),
        );
    }

    private function versAmendeDto(Amende $amende): AmendeDto
    {
        $membre = $amende->getMembre();

        return new AmendeDto(
            $membre->getPrenom() . ' ' . $membre->getNom(),
            $this->formatteur->montant($amende->getMontant()),
            $amende->getNombreJours(),
            $this->formatteur->date($amende->getDateDebut()),
            $this->formatteur->date($amende->getDateFin()),
            $amende->isEstPayee() ? 'Oui' : 'Non',
        );
    }

    private function libellePresence(string $presence): string
    {
        return match ($presence) {
            'present' => 'Présent',
            'absent_represente' => 'Absent (représenté)',
            default => 'Absent',
        };
    }

    /**
     * @param string[] $montants
     */
    private function somme(array $montants): string
    {
        return (string) array_sum(array_map('floatval', $montants));
    }
}