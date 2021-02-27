<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Services\ConversationService;
use App\Services\ParticipantService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Mercure\Update;

/**
 * Class MessageController
 * @package App\Controller
 */
class MessageController extends AbstractController
{
    private ConversationService $conversationService;
    private ParticipantService $participantService;
    private UserService $userService;

    public function __construct(
        ConversationService $conversationService,
        ParticipantService $participantService,
        UserService $userService
    ) {
        $this->conversationService = $conversationService;
        $this->participantService = $participantService;
        $this->userService = $userService;
    }

    /**
     * @Post("newMassage/{id}", name="newMassage")
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return Response
     */
    public function newMessageAction(Request $request, PublisherInterface $publisher): Response
    {
        $message = new Message();

        $user = $this->userService->findByUserId(2);

//        $user = $this->getUser();
        $conversation = $this->conversationService->findByUserId($user->getId());
        $recipient = $this->participantService->findParticipantByConversationIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );

        $update = new Update(
            [
                sprintf('/conversations/%s', $conversation->getId()),
                sprintf('/conversations/%s', $recipient->getUser()->getId()),
            ]
        );

        $publisher($update);

        return $this->json(1);
    }
}
