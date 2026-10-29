<?php

namespace App\Dto;

final readonly class AmendeDto
{
    public function __construct(
        public string $membre,
        public string $montant,
        public int $nombreJours,
        public string $dateDebut,
        public string $dateFin,
        public string $payee,
    ) {
    }
}