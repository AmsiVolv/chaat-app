<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\GroupConversationService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;
use Throwable;

/**
 * @Route("/groupConversations", name="conversations.")
 * Class GroupConversationController
 * @package App\Controller
 */
class GroupConversationController extends AbstractController
{
    private string $mercureDefaultHub;
    private GroupConversationService $groupConversationService;
    private LoggerInterface $logger;
    use CheckRequestDataTrait;

    public function __construct(string $mercureDefaultHub, GroupConversationService $groupConversationService, LoggerInterface $logger)
    {
        $this->mercureDefaultHub = $mercureDefaultHub;
        $this->groupConversationService = $groupConversationService;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="getGroupConversations", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getGroupConversationAction(Request $request): JsonResponse
    {
        $groupConversations = [];
        $userId = $this->getUser()->getId();

        if ($userId) {
            $groupConversations = $this->groupConversationService->getByUserId($userId);
        }

        $this->addLink($request, new Link('mercure', $this->mercureDefaultHub));

        return $this->json($groupConversations);
    }

    /**
     * @Route("/leave", name="leave-group-conversation", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function leaveGroupConversationAction(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $data = $this->checkData(['groupConversationId'], [], $request);

        try {
            $removeUserFromConversation = $this->groupConversationService->removeUserFromConversation($userId, $data->groupConversationId);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Error'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @Route("/enter", name="enter-group-conversation", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function enterGroupConversationAction(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $data = $this->checkData(['groupConversationId'], [], $request);
        $response = [];

        try {
            $groupConversation = $this->groupConversationService->enterUserToConversation($userId, $data->groupConversationId);
            if ($groupConversation) {
                $response = ['id' => $groupConversation->getId(), 'groupName' => $groupConversation->getGroupName()];
            }
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Error'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    /**
     * @Route("/find", name="find-group-conversation", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function findGroupConversationAction(Request $request): JsonResponse
    {
        $data = $this->checkData(['groupConversationGroupName'], [], $request);
        $userId = $this->getUser()->getId();

        try {
            $groupConversations = $this->groupConversationService->findGroupConversation($data->groupConversationGroupName, $userId);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Error'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($groupConversations, Response::HTTP_OK);
    }
}
