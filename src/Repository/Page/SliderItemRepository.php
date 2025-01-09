<?php

namespace App\Repository\Page;

use App\Entity\Page\SliderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SliderItem>
 *
 * @method SliderItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SliderItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SliderItem[]    findAll()
 * @method SliderItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SliderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SliderItem::class);
    }

    //    /**
    //     * @return SliderItem[] Returns an array of SliderItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SliderItem
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
