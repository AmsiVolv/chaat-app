<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use App\Services\ConversationService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Route;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\WebLink\Link;
use Throwable;

/**
 * @Route("/conversations", name="conversations")
 * Class ConversationController
 * @package App\Controller
 */
class ConversationController extends AbstractController
{
    private string $mercureDefaultHub;

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private ConversationRepository $conversationRepository;
    private ConversationService $conversationService;

    private MessageController $messageController;
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;
    private LoggerInterface $logger;

    use CheckRequestDataTrait;

    public function __construct(
        string $mercureDefaultHub,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ConversationRepository $conversationRepository,
        ConversationService $conversationService,
        MessageController $messageController,
        SerializerInterface $serializer,
        MessageBusInterface $bus,
        LoggerInterface $logger
    ) {
        $this->mercureDefaultHub = $mercureDefaultHub;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
        $this->conversationService = $conversationService;
        $this->messageController = $messageController;
        $this->serializer = $serializer;
        $this->bus = $bus;
        $this->logger = $logger;
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $otherUser = $request->get('userId', 0);
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new \Exception("The user was not found");
        }

        // cannot create a conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new \Exception("That's deep but you cannot create a conversation with yourself");
        }

        // Check if conversation already exists
        $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if (count($conversation)) {
            return $this->json([
                'error' => 'This conversation already exists',
            ], Response::HTTP_BAD_REQUEST);
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        $this->messageController->newMessage($request, $conversation);

        return $this->json([
            'id' => $conversation->getId(),
            'username' => $otherUser->getUsername(),
        ], Response::HTTP_CREATED, [], []);
    }


    /**
     * @Route("/", name="getConversations", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getConversations(Request $request): JsonResponse
    {
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());

        foreach ($conversations as &$conversation) {
            if (!$conversation['createdAt']) {
                $conversation['createdAt'] = new DateTime();
            }
        }

        $this->addLink($request, new Link('mercure', $this->mercureDefaultHub));

        return $this->json($conversations);
    }

    /**
     * @Route("/delete", name="deleteConversation", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteConversation(Request $request): JsonResponse
    {
        try {
            $data = $this->checkData(['conversationId'], [], $request);
            $conversationId = (int) $data->conversationId;
            $conversationExist = $this->conversationService->checkIfConversationExist(
                $this->getUser()->getId(),
                $conversationId
            );

            try {
                if ($conversationExist) {
                    $this->conversationService->deleteConversation($conversationId);
                }
            } catch (Throwable $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());

                return new JsonResponse(['status' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @Route("/clear", name="clearConversation", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function clearConversation(Request $request): JsonResponse
    {
        try {
            $data = $this->checkData(['conversationId'], [], $request);
            $conversationId = (int) $data->conversationId;
            $conversationExist = $this->conversationService->checkIfConversationExist(
                $this->getUser()->getId(),
                $conversationId
            );

            try {
                if ($conversationExist) {
                    $this->conversationService->clearConversation($conversationId);
                }
            } catch (Throwable $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());

                return new JsonResponse(['status' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
