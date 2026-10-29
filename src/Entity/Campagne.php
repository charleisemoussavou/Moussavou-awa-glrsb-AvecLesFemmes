<?php

namespace App\Entity;

use App\Repository\CampagneRepository;
use App\Trait\DateTimeTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Campagne
{
    use DateTimeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $periodicite = null;

    #[ORM\Column(length: 50)]
    private ?string $jourRencontre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantFixe = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantCaissePresent = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantCaisseAbsent = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amendeJournaliere = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeCampagne $typeCampagne = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(string $periodicite): static
    {
        $this->periodicite = $periodicite;

        return $this;
    }

    public function getJourRencontre(): ?string
    {
        return $this->jourRencontre;
    }

    public function setJourRencontre(string $jourRencontre): static
    {
        $this->jourRencontre = $jourRencontre;

        return $this;
    }

    public function getMontantFixe(): ?string
    {
        return $this->montantFixe;
    }

    public function setMontantFixe(string $montantFixe): static
    {
        $this->montantFixe = $montantFixe;

        return $this;
    }

    public function getMontantCaissePresent(): ?string
    {
        return $this->montantCaissePresent;
    }

    public function setMontantCaissePresent(string $montantCaissePresent): static
    {
        $this->montantCaissePresent = $montantCaissePresent;

        return $this;
    }

    public function getMontantCaisseAbsent(): ?string
    {
        return $this->montantCaisseAbsent;
    }

    public function setMontantCaisseAbsent(string $montantCaisseAbsent): static
    {
        $this->montantCaisseAbsent = $montantCaisseAbsent;

        return $this;
    }

    public function getAmendeJournaliere(): ?string
    {
        return $this->amendeJournaliere;
    }

    public function setAmendeJournaliere(string $amendeJournaliere): static
    {
        $this->amendeJournaliere = $amendeJournaliere;

        return $this;
    }

    public function getTypeCampagne(): ?TypeCampagne
    {
        return $this->typeCampagne;
    }

    public function setTypeCampagne(?TypeCampagne $typeCampagne): static
    {
        $this->typeCampagne = $typeCampagne;

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }
}