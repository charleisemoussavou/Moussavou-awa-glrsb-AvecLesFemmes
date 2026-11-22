<?php

namespace App\Dto;

final readonly class PageGroupementDto
{
    /**
     * @param GroupementDto[] $groupements
     */
    public function __construct(
        public array $groupements,
        public int $page,
        public int $totalPages,
        public int $total,
        public string $recherche,
    ) {
    }
}