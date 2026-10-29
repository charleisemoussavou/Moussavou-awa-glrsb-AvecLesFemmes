<?php

namespace App\DataFixtures;

use App\Entity\MouvementCaisseSociale;
use App\Repository\AmendeRepository;
use App\Repository\CotisationRepository;
use App\Repository\GroupementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MouvementCaisseSocialeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CotisationRepository $cotisationRepository,
        private readonly AmendeRepository $amendeRepository,
        private readonly GroupementRepository $groupementRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->cotisationRepository->findAll() as $cotisation) {
            $membre = $cotisation->getMembre();
            $mouvement = (new MouvementCaisseSociale())
                ->setMontant($cotisation->getMontantCaisse())
                ->setTypeMouvement('cotisation_caisse')
                ->setDateMouvement($cotisation->getSeance()->getDateSeance())
                ->setLibelle('Cotisation de caisse - ' . $membre->getNom())
                ->setGroupement($membre->getGroupement())
                ->setSeance($cotisation->getSeance());
            $manager->persist($mouvement);
        }

        foreach ($this->amendeRepository->findAll() as $amende) {
            $membre = $amende->getMembre();
            $mouvement = (new MouvementCaisseSociale())
                ->setMontant($amende->getMontant())
                ->setTypeMouvement('amende')
                ->setDateMouvement($amende->getDateDebut())
                ->setLibelle('Amende - ' . $membre->getNom())
                ->setGroupement($membre->getGroupement())
                ->setSeance($amende->getSeance());
            $manager->persist($mouvement);
        }

        foreach ($this->groupementRepository->findAll() as $groupement) {
            $location = (new MouvementCaisseSociale())
                ->setMontant('15000')
                ->setTypeMouvement('location')
                ->setDateMouvement(new \DateTimeImmutable('2026-10-10'))
                ->setLibelle('Location de chaises et bâches')
                ->setGroupement($groupement);
            $manager->persist($location);

            $interet = (new MouvementCaisseSociale())
                ->setMontant('2500')
                ->setTypeMouvement('interet')
                ->setDateMouvement(new \DateTimeImmutable('2026-10-10'))
                ->setLibelle('Intérêts de prêt')
                ->setGroupement($groupement);
            $manager->persist($interet);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CotisationFixtures::class, AmendeFixtures::class];
    }
}