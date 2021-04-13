<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Services\GroupConversationService;
use App\Services\GroupMessagesService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * @Route("/groupMessages", name="group-messages")
 * Class GroupMessagesController
 * @package App\Controller
 */
class GroupMessagesController extends AbstractController
{
    private GroupMessagesService $groupMessagesService;
    private GroupConversationService $groupConversationService;
    private LoggerInterface $logger;
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;

    public function __construct(
        GroupMessagesService $groupMessagesService,
        GroupConversationService $groupConversationService,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        MessageBusInterface $bus
    ) {
        $this->groupMessagesService = $groupMessagesService;
        $this->groupConversationService = $groupConversationService;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->bus = $bus;
    }

    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param GroupConversation $groupConversation
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, GroupConversation $groupConversation): Response
    {
        $groupMessages = [];

        if ($groupConversation) {
            $groupMessages = $this->groupMessagesService->findMessagesByGroupConversationId($groupConversation->getId());
        }

        return $this->json($groupMessages, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="newGroupMessage", methods={"POST"})
     * @param Request $request
     * @param GroupConversation $groupConversation
     * @return JsonResponse
     */
    public function newMessage(
        Request $request,
        GroupConversation $groupConversation
    ): JsonResponse {
        $user = $this->getUser();
        $messageDto = [];

        try {
            $messageDto = $this->groupMessagesService->createMessageFromRequest(
                $request,
                $user->getId(),
                $groupConversation->getId(),
            );

            $messageSerialized = $this->serializer->serialize($messageDto, 'json');
            $groupMembers = $this->groupConversationService->getMembersByGroupId($messageDto->getGroupId());
            $updates = [
                sprintf("/groupConversations/%s", $messageDto->getGroupId()),
                sprintf("/groupConversations/%s", $messageDto->getGroupName()),
            ];

            foreach ($groupMembers as $groupMember) {
                $updates[] = sprintf("/groupConversations/%s", $groupMember['username']);
            }

            $update = new Update(
                $updates,
                $messageSerialized
            );
            $this->bus->dispatch($update);
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage(), $e->getTrace());
        }

        return $this->json($messageDto, Response::HTTP_CREATED, []);
    }
}
