<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\SchoolArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    // /**
    //  * @return Menu[] Returns an array of Menu objects
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
    public function findOneBySomeField($value): ?Menu
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Menu $menu
     * @return Menu
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(Menu $menu): Menu
    {
        $this->getEntityManager()->persist($menu);
        $this->getEntityManager()->flush();

        return $menu;
    }

    public function getByDate(SchoolArea $area): array
    {
        $qb = $this->createQueryBuilder('m');

        $qb->join('m.schoolArea', 'sa')
            ->where($qb->expr()->between('m.createdAt', ':start', ':end'))
            ->andWhere($qb->expr()->eq('sa.id', ':schoolAreaId'))
            ->setParameter('start', (new \DateTime('yesterday'))->format('Y-m-d h:m:i'))
            ->setParameter('end', (new \DateTime('tomorrow'))->format('Y-m-d h:m:i'))
            ->setParameter('schoolAreaId', $area->getId());

        return $qb->getQuery()->getResult();
    }

    public function getMenuByArea(SchoolArea $area): ?array
    {
        $qb = $this->createQueryBuilder('m');

        $qb->select('mc.id, mc.mealName, mc.mealContent')
            ->join('m.schoolArea', 'sa')
            ->join('m.mealContent', 'mc')
            ->where($qb->expr()->eq('sa.id', ':schoolAreaId'))
            ->setParameter('schoolAreaId', $area->getId());

        return $qb->getQuery()->getResult();
    }
}
