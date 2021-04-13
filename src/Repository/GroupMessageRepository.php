<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\GroupMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method GroupMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupMessage[]    findAll()
 * @method GroupMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupMessage::class);
    }

    // /**
    //  * @return GroupMessage[] Returns an array of GroupMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupMessage
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMessagesByGroupConversationId(int $conversationId): array
    {
        $qb = $this->createQueryBuilder('gm');

        $qb->join('gm.groupConversation', 'gc')
            ->where($qb->expr()->eq('gc', ':conversationId'))
            ->setParameter('conversationId', $conversationId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param GroupMessage $groupMessage
     * @return GroupMessage
     * @throws Throwable
     */
    public function store(GroupMessage $groupMessage): GroupMessage
    {
        $this->getEntityManager()->persist($groupMessage);
        $this->getEntityManager()->flush();

        return $groupMessage;
    }
}
