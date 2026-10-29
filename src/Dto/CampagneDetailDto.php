<?php

namespace App\Dto;

final readonly class CampagneDetailDto
{
    /**
     * @param MembreCampagneDto[] $membres
     * @param SeanceResumeDto[] $seances
     */
    public function __construct(
        public int $id,
        public string $nom,
        public string $type,
        public string $periodicite,
        public string $jourRencontre,
        public string $montantFixe,
        public string $montantCaissePresent,
        public string $montantCaisseAbsent,
        public string $amendeJournaliere,
        public string $exercice,
        public int $groupementId,
        public string $groupementNom,
        public array $membres,
        public array $seances,
    ) {
    }
}