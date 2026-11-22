<?php

namespace App\Repository;

use App\Entity\Groupement;
use App\Enum\StatutExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupement>
 */
class GroupementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupement::class);
    }

    /**
     * Une seule requête : chaque groupement arrive avec son exercice en cours déjà chargé.
     *
     * @return Groupement[]
     */
    public function findGroupementsAndExerciceEnCours(): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.exercices', 'e', 'WITH', 'e.statut = :statut')
            ->addSelect('e')
            ->setParameter('statut', StatutExercice::EN_COURS)
            ->orderBy('g.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneAndExerciceEnCours(int $id): ?Groupement
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.exercices', 'e', 'WITH', 'e.statut = :statut')
            ->addSelect('e')
            ->andWhere('g.id = :id')
            ->setParameter('statut', StatutExercice::EN_COURS)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}