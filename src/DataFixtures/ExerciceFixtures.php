<?php

namespace App\DataFixtures;

use App\Entity\Exercice;
use App\Enum\StatutExercice;
use App\Repository\GroupementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExerciceFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly GroupementRepository $groupementRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->groupementRepository->findAll() as $groupement) {
            $precedent = (new Exercice())
                ->setDateDebut(new \DateTimeImmutable('2025-01-01'))
                ->setDateFin(new \DateTimeImmutable('2025-12-31'))
                ->setDateLimitePret(new \DateTimeImmutable('2025-10-10'))
                ->setDateRetribution(new \DateTimeImmutable('2025-11-01'))
                ->setStatut(StatutExercice::CLOTURE)
                ->setGroupement($groupement);
            $manager->persist($precedent);

            $enCours = (new Exercice())
                ->setDateDebut(new \DateTimeImmutable('2026-01-01'))
                ->setDateFin(new \DateTimeImmutable('2026-12-31'))
                ->setDateLimitePret(new \DateTimeImmutable('2026-10-10'))
                ->setDateRetribution(new \DateTimeImmutable('2026-11-01'))
                ->setStatut(StatutExercice::EN_COURS)
                ->setGroupement($groupement);
            $manager->persist($enCours);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GroupementFixtures::class];
    }
}