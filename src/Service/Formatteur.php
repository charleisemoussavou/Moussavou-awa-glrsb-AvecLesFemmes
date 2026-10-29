<?php

namespace App\Service;

class Formatteur
{
    public function date(?\DateTimeInterface $date): string
    {
        return $date ? $date->format('d/m/Y') : '-';
    }

    public function montant(?string $montant): string
    {
        return number_format((float) $montant, 0, ',', ' ') . ' FCFA';
    }
}