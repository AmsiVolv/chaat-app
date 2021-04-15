<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\GroupConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method GroupConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupConversation[]    findAll()
 * @method GroupConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupConversation::class);
    }

    // /**
    //  * @return GroupConversation[] Returns an array of GroupConversation objects
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
    public function findOneBySomeField($value): ?GroupConversation
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param GroupConversation $groupConversation
     * @return GroupConversation
     * @throws Throwable
     */
    public function store(GroupConversation $groupConversation): GroupConversation
    {
        $this->getEntityManager()->persist($groupConversation);
        $this->getEntityManager()->flush();

        return $groupConversation;
    }

    public function getByUserId(int $userId): array
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->select('gc.id, gc.groupName, gc.groupColor, gc.updatedAt, gc.createdAt, gm.messageContent as content')
            ->join('gc.user', 'u')
            ->leftJoin('gc.lastMessage', 'gm')
            ->where($qb->expr()->eq('u.id', ':userId'))
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $conversationId
     * @return GroupConversation|null
     * @throws Throwable
     */
    public function getByConversationId(int $conversationId): ?GroupConversation
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->where($qb->expr()->eq('gc.id', ':conversationId'))
            ->setParameter('conversationId', $conversationId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getMembersByGroupId(int $groupId): array
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->select('u.username')
            ->join('gc.user', 'u')
            ->where($qb->expr()->eq('gc.id', ':groupId'))
            ->setParameter('groupId', $groupId);

        return $qb->getQuery()->getResult();
    }

    public function getByUserIdAndConversationId(int $userId, int $groupConversationId): array
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->join('gc.user', 'u')
            ->where($qb->expr()->eq('gc.id', ':groupId'))
            ->andWhere($qb->expr()->eq('u.id', ':userId'))
            ->setParameter('groupId', $groupConversationId)
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $groupConversationId
     * @return GroupConversation|null
     * @throws Throwable
     */
    public function getById(int $groupConversationId): ?GroupConversation
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->where($qb->expr()->eq('gc.id', ':groupId'))
            ->setParameter('groupId', $groupConversationId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findGroupConversationByName(string $groupConversationGroupName, int $userId): array
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->select('gc.id, gc.groupName')
            ->where($qb->expr()->like('gc.groupName', ':groupName'))
            ->setParameter('groupName', sprintf('%%%s%%', $groupConversationGroupName))
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function getParticipantsByGroupId(int $groupId): array
    {
        $qb = $this->createQueryBuilder('gc');

        $qb->select('u.id, u.username, u.iconColor')
            ->join('gc.user', 'u')
            ->where($qb->expr()->eq('gc.id', ':groupId'))
            ->setParameter('groupId', $groupId);

        return $qb->getQuery()->getResult();
    }
}
