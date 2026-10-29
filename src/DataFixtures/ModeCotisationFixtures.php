<?php

namespace App\DataFixtures;

use App\Entity\ModeCotisation;
use App\Repository\CampagneRepository;
use App\Repository\MembreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModeCotisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CampagneRepository $campagneRepository,
        private readonly MembreRepository $membreRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->campagneRepository->findAll() as $campagne) {
            $groupement = $campagne->getExercice()->getGroupement();
            $membres = $this->membreRepository->findBy(['groupement' => $groupement], ['id' => 'ASC']);

            foreach ($membres as $i => $membre) {
                $mode = (new ModeCotisation())
                    ->setMembre($membre)
                    ->setCampagne($campagne)
                    ->setIsLibre($i % 4 === 0);
                $manager->persist($mode);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CampagneFixtures::class, MembreFixtures::class];
    }
}