<?php

namespace App\Repository;

use App\Entity\Campagne;
use App\Entity\MouvementCaisseSociale;
use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MouvementCaisseSociale>
 */
class MouvementCaisseSocialeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementCaisseSociale::class);
    }

    /**
     * @return array<int, string> total de la caisse sociale, indexé par identifiant de séance
     */
    public function totauxParSeance(Campagne $campagne): array
    {
        $lignes = $this->createQueryBuilder('mv')
            ->select('IDENTITY(mv.seance) AS seanceId', 'SUM(mv.montant) AS total')
            ->join('mv.seance', 's')
            ->andWhere('s.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->groupBy('mv.seance')
            ->getQuery()
            ->getArrayResult();

        return array_column($lignes, 'total', 'seanceId');
    }

    public function totalPourSeance(Seance $seance): string
    {
        $total = $this->createQueryBuilder('mv')
            ->select('SUM(mv.montant)')
            ->andWhere('mv.seance = :seance')
            ->setParameter('seance', $seance)
            ->getQuery()
            ->getSingleScalarResult();

        return $total ?? '0';
    }
}