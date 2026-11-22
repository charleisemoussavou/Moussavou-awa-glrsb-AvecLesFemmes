<?php

namespace App\Repository;

use App\Entity\Groupement;
use App\Enum\StatutExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function save(Groupement $groupement): void
    {
        $this->getEntityManager()->persist($groupement);
        $this->getEntityManager()->flush();
    }

    /**
     * Une page de groupements, filtrés par téléphone ou ville, chacun avec son exercice en cours.
     *
     * @return Paginator<Groupement>
     */
    public function findPageAndExerciceEnCours(?string $recherche, int $page, int $parPage): Paginator
    {
        $qb = $this->createQueryBuilder('g')
            ->leftJoin('g.exercices', 'e', 'WITH', 'e.statut = :statut')
            ->addSelect('e')
            ->setParameter('statut', StatutExercice::EN_COURS)
            ->orderBy('g.nom', 'ASC')
            ->setFirstResult(($page - 1) * $parPage)
            ->setMaxResults($parPage);

        if ($recherche !== null) {
            $qb->andWhere('g.telephone LIKE :recherche OR LOWER(g.adresse) LIKE :recherche')
                ->setParameter('recherche', '%' . mb_strtolower($recherche) . '%');
        }

        return new Paginator($qb->getQuery(), true);
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