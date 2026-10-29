<?php

namespace App\Dto;

final readonly class MembreCampagneDto
{
    public function __construct(
        public string $nomComplet,
        public string $telephone,
        public string $adresse,
        public string $modeCotisation,
    ) {
    }
}