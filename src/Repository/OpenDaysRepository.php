<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\OpenDays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method OpenDays|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpenDays|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpenDays[]    findAll()
 * @method OpenDays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpenDaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpenDays::class);
    }

    // /**
    //  * @return OpenDays[] Returns an array of OpenDays objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpenDays
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param OpenDays $openDay
     * @return OpenDays
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(OpenDays $openDay): OpenDays
    {
        $this->getEntityManager()->persist($openDay);
        $this->getEntityManager()->flush();

        return $openDay;
    }

    public function getByDate(): ?array
    {
        $qb = $this->createQueryBuilder('od');

        $qb->select('od.openDaysDescription, od.openDayDate, od.link')
            ->where($qb->expr()->between('od.createdAt', ':openDayDateStart', ':openDayDateEnd'))
            ->setParameter('openDayDateStart', (new \DateTime('yesterday'))->format('Y-m-d h:m:i'))
            ->setParameter('openDayDateEnd', (new \DateTime('tomorrow'))->format('Y-m-d h:m:i'));

        return $qb->getQuery()->getResult();
    }
}
