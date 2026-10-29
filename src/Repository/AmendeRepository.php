<?php

namespace App\Repository;

use App\Entity\Amende;
use App\Entity\Campagne;
use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amende>
 */
class AmendeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amende::class);
    }

    /**
     * @return Amende[]
     */
    public function findBySeance(Seance $seance): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.membre', 'm')
            ->addSelect('m')
            ->andWhere('a.seance = :seance')
            ->setParameter('seance', $seance)
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<int, string> total des amendes, indexé par identifiant de séance
     */
    public function totauxParSeance(Campagne $campagne): array
    {
        $lignes = $this->createQueryBuilder('a')
            ->select('IDENTITY(a.seance) AS seanceId', 'SUM(a.montant) AS total')
            ->join('a.seance', 's')
            ->andWhere('s.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->groupBy('a.seance')
            ->getQuery()
            ->getArrayResult();

        return array_column($lignes, 'total', 'seanceId');
    }
}