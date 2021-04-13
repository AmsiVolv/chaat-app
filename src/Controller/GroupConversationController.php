<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\GroupConversationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

/**
 * @Route("/groupConversations", name="conversations.")
 * Class GroupConversationController
 * @package App\Controller
 */
class GroupConversationController extends AbstractController
{
    private string $mercureDefaultHub;
    private GroupConversationService $groupConversationService;

    public function __construct(string $mercureDefaultHub, GroupConversationService $groupConversationService)
    {
        $this->mercureDefaultHub = $mercureDefaultHub;
        $this->groupConversationService = $groupConversationService;
    }

    /**
     * @Route("/", name="getGroupConversations", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getGroupConversation(Request $request): JsonResponse
    {
        $groupConversations = [];
        $userId = $this->getUser()->getId();

        if ($userId) {
            $groupConversations = $this->groupConversationService->getByUserId($userId);
        }

        $this->addLink($request, new Link('mercure', $this->mercureDefaultHub));

        return $this->json($groupConversations);
    }
}
