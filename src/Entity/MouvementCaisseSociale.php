<?php

namespace App\Entity;

use App\Repository\MouvementCaisseSocialeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MouvementCaisseSocialeRepository::class)]
class MouvementCaisseSociale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
