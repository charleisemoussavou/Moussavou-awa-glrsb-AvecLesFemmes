<?php

namespace App\DataFixtures;

use App\Entity\Campagne;
use App\Enum\StatutExercice;
use App\Repository\ExerciceRepository;
use App\Repository\TypeCampagneRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampagneFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly ExerciceRepository $exerciceRepository,
        private readonly TypeCampagneRepository $typeCampagneRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $calebasse = $this->typeCampagneRepository->findOneBy(['nom' => 'Épargne calebasse']);
        $tontine = $this->typeCampagneRepository->findOneBy(['nom' => 'Tontine']);

        $modeles = [
            ['Épargne calebasse', $calebasse, 'hebdomadaire', 'Dimanche', '5000'],
            ['Tontine mensuelle', $tontine, 'mensuel', 'Premier dimanche', '10000'],
        ];

        $exercices = $this->exerciceRepository->findBy(['statut' => StatutExercice::EN_COURS]);

        foreach ($exercices as $exercice) {
            foreach ($modeles as [$nom, $type, $periodicite, $jour, $montant]) {
                $campagne = (new Campagne())
                    ->setNom($nom)
                    ->setTypeCampagne($type)
                    ->setPeriodicite($periodicite)
                    ->setJourRencontre($jour)
                    ->setMontantFixe($montant)
                    ->setMontantCaissePresent('100')
                    ->setMontantCaisseAbsent('300')
                    ->setAmendeJournaliere('350')
                    ->setExercice($exercice);
                $manager->persist($campagne);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ExerciceFixtures::class, TypeCampagneFixtures::class];
    }
}