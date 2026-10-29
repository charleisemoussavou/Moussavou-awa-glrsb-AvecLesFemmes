<?php

namespace App\Dto;

final readonly class SeanceDetailDto
{
    /**
     * @param CotisationDto[] $cotisations
     * @param AmendeDto[] $amendes
     */
    public function __construct(
        public int $id,
        public string $date,
        public int $campagneId,
        public string $campagneNom,
        public array $cotisations,
        public array $amendes,
        public string $totalCotisations,
        public string $totalAmendes,
        public string $totalCaisse,
    ) {
    }
}