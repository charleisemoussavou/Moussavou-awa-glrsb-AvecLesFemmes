<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class GroupementFormDto
{
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(max: 255, maxMessage: 'Le nom est trop long.')]
    public ?string $nom = null;

    #[Assert\NotBlank(message: 'Le téléphone est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9 +]{9,20}$/', message: 'Numéro de téléphone invalide.')]
    public ?string $telephone = null;

    #[Assert\Length(max: 255, maxMessage: "L'adresse est trop longue.")]
    public ?string $adresse = null;
}