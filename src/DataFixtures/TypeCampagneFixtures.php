<?php

namespace App\DataFixtures;

use App\Entity\TypeCampagne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeCampagneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['Épargne calebasse', 'Tontine'] as $nom) {
            $type = (new TypeCampagne())->setNom($nom);
            $manager->persist($type);
        }

        $manager->flush();
    }
}