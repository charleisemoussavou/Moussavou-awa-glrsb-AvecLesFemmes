<?php

namespace App\Dto;

final readonly class CotisationDto
{
    public function __construct(
        public string $membre,
        public string $montant,
        public string $montantCaisse,
        public string $datePaiement,
        public string $modePaiement,
        public string $presence,
    ) {
    }
}