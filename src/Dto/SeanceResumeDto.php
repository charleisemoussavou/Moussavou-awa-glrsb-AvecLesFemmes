<?php

namespace App\Dto;

final readonly class SeanceResumeDto
{
    public function __construct(
        public int $id,
        public string $date,
        public string $totalCotisations,
        public string $totalAmendes,
        public string $totalCaisse,
    ) {
    }
}