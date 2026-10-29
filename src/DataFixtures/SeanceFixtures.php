<?php

namespace App\DataFixtures;

use App\Entity\Seance;
use App\Repository\CampagneRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeanceFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly CampagneRepository $campagneRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->campagneRepository->findAll() as $campagne) {
            for ($i = 0; $i < 3; $i++) {
                $seance = (new Seance())
                    ->setDateSeance((new \DateTimeImmutable('2026-10-04'))->modify("+$i week"))
                    ->setCampagne($campagne);
                $manager->persist($seance);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CampagneFixtures::class];
    }
}