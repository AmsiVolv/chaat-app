<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ChatbotMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method ChatbotMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatbotMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatbotMessages[]    findAll()
 * @method ChatbotMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatbotMessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatbotMessages::class);
    }

    // /**
    //  * @return ChatbotMessages[] Returns an array of ChatbotMessages objects
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
    public function findOneBySomeField($value): ?ChatbotMessages
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param ChatbotMessages $chatmBotMessage
     * @return ChatbotMessages
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(ChatbotMessages $chatmBotMessage): ChatbotMessages
    {
        $this->getEntityManager()->persist($chatmBotMessage);
        $this->getEntityManager()->flush();

        return $chatmBotMessage;
    }

    /**
     * @param int $externalId
     * @param int $userId
     * @return ChatbotMessages|null
     * @throws NonUniqueResultException
     */
    public function findByExternalIdAndUserId(int $externalId, int $userId): ?ChatbotMessages
    {
        $qb = $this->createQueryBuilder('cm');

        $qb->where($qb->expr()->eq('cm.externalId', ':externalId'))
            ->andWhere($qb->expr()->eq('cm.user', ':userId'))
            ->setParameter('externalId', $externalId)
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
