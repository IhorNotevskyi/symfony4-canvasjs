<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function deleteProduct(Product $product)
    {
        return $this
            ->createQueryBuilder('p')
            ->delete()
            ->where('p.id = :id')
            ->setParameter('id', $product)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFirstCurrentPrice(Product $product, $date)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('i.price', 'DATE_DIFF(i.finishDate, i.startDate) + 1 AS difference')
            ->leftJoin('p.interspaces', 'i')
            ->where('p.id = :id')
            ->andWhere(':date BETWEEN i.startDate AND i.finishDate')
            ->setParameter('id', $product)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getSecondCurrentPrice(Product $product, $date)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('i.price', 'i.startDate')
            ->leftJoin('p.interspaces', 'i')
            ->where('p.id = :id')
            ->andWhere(':date BETWEEN i.startDate AND i.finishDate')
            ->orderBy('i.startDate', 'DESC')
            ->setParameter('id', $product)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getDataForFirstChart(Product $product)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('i.price', 'i.startDate', 'i.finishDate', 'DATE_DIFF(i.finishDate, i.startDate) + 1 AS difference')
            ->leftJoin('p.interspaces', 'i')
            ->where('p.id = :id')
            ->orderBy('i.startDate', 'ASC')
            ->addOrderBy('difference', 'ASC')
            ->setParameter('id', $product)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getDataForSecondChart(Product $product)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('i.price', 'i.startDate', 'i.finishDate')
            ->leftJoin('p.interspaces', 'i')
            ->where('p.id = :id')
            ->orderBy('i.startDate', 'DESC')
            ->setParameter('id', $product)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getStaticDataForChart(Product $product)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('CURRENT_DATE() AS currentDate')
            ->where('p.id = :id')
            ->setParameter('id', $product)
            ->getQuery()
            ->getResult()
        ;
    }
}