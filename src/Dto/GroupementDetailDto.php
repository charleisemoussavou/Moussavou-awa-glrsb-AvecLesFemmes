<?php

namespace App\Dto;

final readonly class GroupementDetailDto
{
    /**
     * @param CampagneDto[] $campagnes
     */
    public function __construct(
        public GroupementDto $groupement,
        public array $campagnes,
    ) {
    }
}