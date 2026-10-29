<?php

namespace App\Dto;

final readonly class GroupementDto
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $telephone,
        public string $adresse,
        public ?ExerciceDto $exerciceEnCours,
    ) {
    }
}