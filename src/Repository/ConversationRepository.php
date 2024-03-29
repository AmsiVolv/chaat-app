<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    private ParticipantRepository $participantRepository;
    private MessageRepository $messageRepository;

    public function __construct(
        ManagerRegistry $registry,
        ParticipantRepository $participantRepository,
        MessageRepository $messageRepository
    ) {
        parent::__construct($registry, Conversation::class);
        $this->participantRepository = $participantRepository;
        $this->messageRepository = $messageRepository;
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findConversationByParticipants(int $otherUserId, int $myId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select($qb->expr()->count('p.conversation'))
            ->innerJoin('c.participants', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.user', ':me'),
                    $qb->expr()->eq('p.user', ':otherUser')
                )
            )
            ->groupBy('p.conversation')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversation'),
                    2
                )
            )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherUserId,
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function findConversationsByUser(int $userId): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
            select('otherUser.iconColor', 'otherUser.username', 'c.id as conversationId', 'lm.content', 'lm.createdAt')
            ->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.user', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessage', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->where('meUser.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('lm.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function findConversationsByUserAndId(int $userId, int $conversationId): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.user', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessage', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->where('meUser.id = :user')
            ->where($qb->expr()->eq('c.id', ':conversationId'))
            ->setParameter('user', $userId)
            ->setParameter('conversationId', $conversationId)
            ->orderBy('lm.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

//    public function checkIfUserisParticipant(int $conversationId, int $userId)
//    {
//        $qb = $this->createQueryBuilder('c');
//        $qb
//            ->innerJoin('c.participants', 'p')
//            ->where('c.id = :conversationId')
//            ->andWhere(
//                $qb->expr()->eq('p.user', ':userId')
//            )
//            ->setParameters([
//                'conversationId' => $conversationId,
//                'userId' => $userId,
//            ])
//        ;
//
//        return $qb->getQuery()->getOneOrNullResult();
//    }

    /**
     * @param int $conversationId
     * @return Conversation|null
     * @throws Throwable
     */
    public function getById(int $conversationId): ?Conversation
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where($qb->expr()->eq('c.id', ':id'))
            ->setParameter('id', $conversationId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Conversation $conversation
     * @return Conversation
     * @throws Throwable
     */
    public function store(Conversation $conversation): Conversation
    {
        $this->getEntityManager()->persist($conversation);
        $this->getEntityManager()->flush($conversation);

        return $conversation;
    }

    /**
     * @param int $conversationId
     * @throws ORMException
     * @throws Throwable
     */
    public function deleteConversation(int $conversationId): void
    {
        $conversation = $this->getById($conversationId);
        $conversation->removeLastMessage();
        $this->messageRepository->deleteMessages($conversation->getMessages());

        $this->getEntityManager()->remove($conversation);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $conversationId
     * @throws ORMException
     * @throws Throwable
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function clearConversation(int $conversationId): void
    {
        $conversation = $this->getById($conversationId);
        $conversation->removeLastMessage();
        $this->messageRepository->deleteMessages($conversation->getMessages());

        $this->getEntityManager()->persist($conversation);
        $this->getEntityManager()->flush();
    }
}
