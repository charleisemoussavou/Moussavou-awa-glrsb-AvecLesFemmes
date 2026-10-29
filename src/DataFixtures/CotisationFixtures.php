<?php

namespace App\DataFixtures;

use App\Entity\Cotisation;
use App\Repository\ModeCotisationRepository;
use App\Repository\SeanceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CotisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SeanceRepository $seanceRepository,
        private readonly ModeCotisationRepository $modeCotisationRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $modesPaiement = ['especes', 'wave', 'orange_money'];

        foreach ($this->seanceRepository->findAll() as $seance) {
            $campagne = $seance->getCampagne();
            $modes = $this->modeCotisationRepository->findBy(['campagne' => $campagne], ['id' => 'ASC']);

            foreach ($modes as $i => $mode) {
                $position = $i % 5;

                if ($position === 4) {
                    continue;
                }

                $representee = $position === 3;
                $montant = $mode->isLibre()
                    ? (string) ((int) $campagne->getMontantFixe() + 1000)
                    : $campagne->getMontantFixe();

                $cotisation = (new Cotisation())
                    ->setMontant($montant)
                    ->setMontantCaisse($representee ? $campagne->getMontantCaisseAbsent() : $campagne->getMontantCaissePresent())
                    ->setDatePaiement($seance->getDateSeance())
                    ->setModePaiement($modesPaiement[$i % 3])
                    ->setPresence($representee ? 'absent_represente' : 'present')
                    ->setMembre($mode->getMembre())
                    ->setSeance($seance);
                $manager->persist($cotisation);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SeanceFixtures::class, ModeCotisationFixtures::class];
    }
}