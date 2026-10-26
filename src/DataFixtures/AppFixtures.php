<?php

namespace App\DataFixtures;

use App\Entity\Campagne;
use App\Entity\Cotisation;
use App\Entity\Groupement;
use App\Entity\Membre;
use App\Entity\ModeCotisation;
use App\Entity\Role;
use App\Entity\Seance;
use App\Entity\TypeCampagne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $groupements = [];
        foreach (['Femmes Solidaires', 'Teranga Épargne', 'Jigeen Ak Ndaw'] as $nom) {
            $groupement = (new Groupement())->setNom($nom);
            $manager->persist($groupement);
            $groupements[] = $groupement;
        }

        $roles = [];
        foreach (['Admin', 'Trésorier', 'Membre'] as $nom) {
            $role = (new Role())->setNom($nom);
            $manager->persist($role);
            $roles[$nom] = $role;
        }

        $tontine = (new TypeCampagne())->setNom('Tontine');
        $epargne = (new TypeCampagne())->setNom('Épargne');
        $manager->persist($tontine);
        $manager->persist($epargne);

        $membres = [];
        for ($i = 0; $i < 15; $i++) {
            $nomRole = match ($i) {
                0 => 'Admin',
                1 => 'Trésorier',
                default => 'Membre',
            };

            $membre = (new Membre())
                ->setNomComplet($faker->name('female'))
                ->setTelephone($faker->phoneNumber())
                ->setAdresse($faker->streetAddress())
                ->setLogin($faker->unique()->userName())
                ->setPassword(password_hash('password', PASSWORD_BCRYPT))
                ->setGroupement($faker->randomElement($groupements))
                ->setRole($roles[$nomRole]);

            $manager->persist($membre);
            $membres[] = $membre;
        }

        $modelesCampagnes = [
            ['Tontine hebdomadaire', $tontine, '5000'],
            ['Tontine mensuelle', $tontine, '20000'],
            ['Épargne collective', $epargne, '10000'],
            ['Épargne de fin d\'année', $epargne, '7500'],
        ];

        foreach ($modelesCampagnes as [$nom, $type, $montant]) {
            $campagne = (new Campagne())
                ->setNom($nom)
                ->setDateDebut(new \DateTimeImmutable('-2 months'))
                ->setDateFin(new \DateTimeImmutable('+4 months'))
                ->setMontant($montant)
                ->setTypeCampagne($type);
            $manager->persist($campagne);

            $participants = $faker->randomElements($membres, 8);

            foreach ($participants as $participant) {
                $mode = (new ModeCotisation())
                    ->setIsLibre($faker->boolean())
                    ->setMembre($participant)
                    ->setCampagne($campagne);
                $manager->persist($mode);
            }

            for ($s = 1; $s <= 3; $s++) {
                $dateSeance = new \DateTimeImmutable("-$s weeks");
                $seance = (new Seance())
                    ->setDate($dateSeance)
                    ->setCampagne($campagne);
                $manager->persist($seance);

                foreach ($participants as $participant) {
                    $cotisation = (new Cotisation())
                        ->setMontant($montant)
                        ->setDatePaiement($dateSeance)
                        ->setMembre($participant)
                        ->setSeance($seance);
                    $manager->persist($cotisation);
                }
            }
        }

        $manager->flush();
    }
}