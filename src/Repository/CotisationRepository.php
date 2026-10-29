<?php

namespace App\Repository;

use App\Entity\Campagne;
use App\Entity\Cotisation;
use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cotisation>
 */
class CotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotisation::class);
    }

    /**
     * @return Cotisation[]
     */
    public function findBySeance(Seance $seance): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.membre', 'm')
            ->addSelect('m')
            ->andWhere('c.seance = :seance')
            ->setParameter('seance', $seance)
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<int, string> total des cotisations, indexé par identifiant de séance
     */
    public function totauxParSeance(Campagne $campagne): array
    {
        $lignes = $this->createQueryBuilder('c')
            ->select('IDENTITY(c.seance) AS seanceId', 'SUM(c.montant) AS total')
            ->join('c.seance', 's')
            ->andWhere('s.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->groupBy('c.seance')
            ->getQuery()
            ->getArrayResult();

        return array_column($lignes, 'total', 'seanceId');
    }
}