<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use App\Repository\GroupementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MembreFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly GroupementRepository $groupementRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $groupements = $this->groupementRepository->findBy([], ['id' => 'ASC']);

        $membres = [
            ['Diop', 'Fatou', '770000001'],
            ['Ndiaye', 'Awa', '770000002'],
            ['Sow', 'Khady', '770000003'],
            ['Fall', 'Mariama', '770000004'],
            ['Ba', 'Aminata', '770000005'],
            ['Sarr', 'Ndeye', '770000006'],
            ['Gueye', 'Rokhaya', '770000007'],
            ['Diouf', 'Coumba', '770000008'],
            ['Thiam', 'Seynabou', '770000009'],
            ['Cissé', 'Astou', '770000010'],
            ['Mbaye', 'Binta', '770000011'],
            ['Faye', 'Marième', '770000012'],
            ['Kane', 'Dieynaba', '770000013'],
            ['Seck', 'Adja', '770000014'],
            ['Ka', 'Yacine', '770000015'],
        ];

        foreach ($membres as $i => [$nom, $prenom, $telephone]) {
            $membre = (new Membre())
                ->setNom($nom)
                ->setPrenom($prenom)
                ->setTelephone($telephone)
                ->setAdresse('Dakar')
                ->setGroupement($groupements[intdiv($i, 5)]);
            $manager->persist($membre);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GroupementFixtures::class];
    }
}