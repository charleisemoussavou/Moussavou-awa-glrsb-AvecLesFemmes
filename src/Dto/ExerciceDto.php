<?php

namespace App\Dto;

final readonly class ExerciceDto
{
    public function __construct(
        public int $id,
        public string $dateDebut,
        public string $dateFin,
        public string $dateLimitePret,
        public string $dateRetribution,
        public string $statut,
    ) {
    }
}