<?php

namespace App\Repository\Page;

use App\Entity\Page\UrlRedirection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrlRedirection>
 *
 * @method UrlRedirection|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlRedirection|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlRedirection[]    findAll()
 * @method UrlRedirection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlRedirectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlRedirection::class);
    }

    //    /**
    //     * @return UrlRedirection[] Returns an array of UrlRedirection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UrlRedirection
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
