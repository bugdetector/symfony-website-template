<?php

namespace App\Repository\Page;

use App\Entity\Page\AccordionItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccordionItem>
 *
 * @method AccordionItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccordionItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccordionItem[]    findAll()
 * @method AccordionItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccordionItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccordionItem::class);
    }

    //    /**
    //     * @return AccordionItem[] Returns an array of AccordionItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AccordionItem
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
