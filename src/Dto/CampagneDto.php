<?php

namespace App\Dto;

final readonly class CampagneDto
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $type,
        public string $periodicite,
        public string $jourRencontre,
        public string $montantFixe,
        public string $exercice,
        public string $statutExercice,
    ) {
    }
}