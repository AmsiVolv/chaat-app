<?php

namespace App\Repository;

use App\Entity\SchoolArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method SchoolArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolArea[]    findAll()
 * @method SchoolArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolAreaRepository extends ServiceEntityRepository
{
    public const ZIZKOV_ID = 1;
    public const JAROV_ID = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolArea::class);
    }

    // /**
    //  * @return SchoolArea[] Returns an array of SchoolArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SchoolArea
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param string $areal
     * @return SchoolArea|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByArelTitle(string $areal): ?SchoolArea
    {
        $qb = $this->createQueryBuilder('sa');

        $qb->where($qb->expr()->eq('sa.areaTitle', ':areal'))
            ->setParameter('areal', $areal);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
