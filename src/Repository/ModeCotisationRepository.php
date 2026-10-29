<?php

namespace App\Repository;

use App\Entity\Campagne;
use App\Entity\ModeCotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModeCotisation>
 */
class ModeCotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeCotisation::class);
    }

    /**
     * @return ModeCotisation[]
     */
    public function findByCampagne(Campagne $campagne): array
    {
        return $this->createQueryBuilder('mc')
            ->join('mc.membre', 'm')
            ->addSelect('m')
            ->andWhere('mc.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}