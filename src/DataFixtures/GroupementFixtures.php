<?php

namespace App\DataFixtures;

use App\Entity\Groupement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $groupements = [
            ['Femmes Solidaires', '771234567', 'Médina, Dakar'],
            ['Teranga Épargne', '772345678', 'Guédiawaye'],
            ['Jigeen Ak Ndaw', '773456789', null],
        ];

        foreach ($groupements as [$nom, $telephone, $adresse]) {
            $groupement = (new Groupement())
                ->setNom($nom)
                ->setTelephone($telephone)
                ->setAdresse($adresse);
            $manager->persist($groupement);
        }

        $manager->flush();
    }
}