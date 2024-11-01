<?php

namespace App\Repository;

use App\Entity\Tache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tache>
 *
 * @method Tache|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tache|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tache[]    findAll()
 * @method Tache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tache::class);
    }

    /**
     * @return Tache[] Returns an array of Tache objects excluding those with status 'fini'
     */
    public function findActiveTasks(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.statut != :statut')
            ->setParameter('statut', 'fini')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
