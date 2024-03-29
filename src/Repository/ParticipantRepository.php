<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findParticipantByConversationIdAndUserId(int $conversationId, int $userId): Participant
    {
        $qb = $this->createQueryBuilder('p');
        $qb->
        where(
            $qb->expr()->andX(
                $qb->expr()->eq('p.conversation', ':conversationId'),
                $qb->expr()->neq('p.user', ':userId')
            )
        )
        ->setParameters([
            'conversationId' => $conversationId,
            'userId' => $userId,
        ]);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findParticipantByConverstionIdAndUserId(int $conversationId, int $userId)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->
        where(
            $qb->expr()->andX(
                $qb->expr()->eq('p.conversation', ':conversationId'),
                $qb->expr()->neq('p.user', ':userId')
            )
        )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId,
            ]);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Collection<Participant> $participants
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteParticipant(Collection $participants): void
    {
        foreach ($participants as $participant) {
            $this->getEntityManager()->remove($participant);
            $this->getEntityManager()->flush();
        }
    }
}
