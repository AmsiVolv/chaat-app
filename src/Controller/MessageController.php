<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conversation;
use App\Repository\UserRepository;
use App\Services\MessageService;
use App\Services\ParticipantService;
use FOS\RestBundle\Controller\Annotations\Route;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * @Route("/messages", name="messages")
 * Class MessageController
 * @package App\Controller
 */
class MessageController extends AbstractController
{
    private const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'recipientUser' => ['username', 'iconColor']];

    private MessageService $messageService;
    private UserRepository $userRepository;
    private ParticipantService $participantService;
    private PublisherInterface $publisher;
    private LoggerInterface $logger;

    private SerializerInterface $serializer;
    private MessageBusInterface $bus;

    public function __construct(
        MessageService $messageService,
        UserRepository $userRepository,
        ParticipantService $participantService,
        PublisherInterface $publisher,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        MessageBusInterface $bus,
    ) {
        $this->messageService = $messageService;
        $this->userRepository = $userRepository;
        $this->participantService = $participantService;
        $this->publisher = $publisher;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->bus = $bus;
    }

    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param Request $request
     * @param Conversation $conversation
     * @return Response
     */
    public function index(Request $request, Conversation $conversation): Response
    {
        $messages = $this->messageService->findMessageByConversationId(
            $conversation->getId()
        );

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE,
        ]);
    }

    /**
     * @Route("/{id}", name="newMessage", methods={"POST"})
     * @param Request $request
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function newMessage(
        Request $request,
        Conversation $conversation
    ): JsonResponse {
        $user = $this->getUser();
        $message = null;

        $recipient = $this->participantService->findParticipantByConversationIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );

        try {
            $message = $this->messageService->createMessageFromRequest(
                $request,
                $user->getId(),
                $recipient->getUser()->getId(),
                $conversation->getId()
            );
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage(), $e->getTrace());
        }

        $messageSerialized = $this->serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'recipientUser' => ['username'], 'conversation' => ['id']],
        ]);

        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
                sprintf("/conversations/%s", $recipient->getUser()->getUsername()),
            ],
            $messageSerialized
        );

        $this->bus->dispatch($update);
        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE,
        ]);
    }
}
