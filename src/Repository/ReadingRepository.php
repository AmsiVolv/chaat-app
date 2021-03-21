<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Reading|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reading|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reading[]    findAll()
 * @method Reading[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reading::class);
    }

    // /**
    //  * @return Reading[] Returns an array of Reading objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reading
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Reading $reading
     * @return Reading
     * @throws Throwable
     */
    public function store(Reading $reading): Reading
    {
        $this->getEntityManager()->persist($reading);
        $this->getEntityManager()->flush($reading);

        return $reading;
    }

    /**
     * @param string|null $ISBN
     * @return Reading|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByIsbn(?string $ISBN): ?Reading
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where($qb->expr()->eq('r.ISBN', ':ISBN'))
            ->setParameter('ISBN', $ISBN);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
