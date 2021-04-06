<?php

namespace App\Repository;

use App\Entity\MenuMeals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuMeals|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuMeals|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuMeals[]    findAll()
 * @method MenuMeals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuMealsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuMeals::class);
    }

    // /**
    //  * @return MenuMeals[] Returns an array of MenuMeals objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuMeals
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function store(MenuMeals $menuMeal): MenuMeals
    {
        $this->getEntityManager()->persist($menuMeal);
        $this->getEntityManager()->flush();

        return $menuMeal;
    }
}
