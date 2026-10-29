<?php

namespace App\DataFixtures;

use App\Entity\Amende;
use App\Repository\ModeCotisationRepository;
use App\Repository\SeanceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AmendeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SeanceRepository $seanceRepository,
        private readonly ModeCotisationRepository $modeCotisationRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $jours = 3;

        foreach ($this->seanceRepository->findAll() as $seance) {
            $campagne = $seance->getCampagne();
            $modes = $this->modeCotisationRepository->findBy(['campagne' => $campagne], ['id' => 'ASC']);

            foreach ($modes as $i => $mode) {
                if ($i % 5 !== 4) {
                    continue;
                }

                $amende = (new Amende())
                    ->setMontant((string) ($jours * (int) $campagne->getAmendeJournaliere()))
                    ->setDateDebut($seance->getDateSeance())
                    ->setDateFin($seance->getDateSeance()->modify("+$jours days"))
                    ->setNombreJours($jours)
                    ->setEstPayee($seance->getDateSeance() <= new \DateTimeImmutable('2026-10-04 23:59'))
                    ->setMembre($mode->getMembre())
                    ->setSeance($seance);
                $manager->persist($amende);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SeanceFixtures::class, ModeCotisationFixtures::class];
    }
}