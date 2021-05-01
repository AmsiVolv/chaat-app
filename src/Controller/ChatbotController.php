<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\ChatbotMessagesService;
use App\Services\FacultyService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/chatbot", name="chatbot")
 * Class ChatbotController
 * @package App\Controller
 */
class ChatbotController extends AbstractController
{
    use CheckRequestDataTrait;

    private ChatbotMessagesService $chatbotMessagesService;
    private LoggerInterface $logger;

    public function __construct(ChatbotMessagesService $chatbotMessagesService, LoggerInterface $logger)
    {
        $this->chatbotMessagesService = $chatbotMessagesService;
        $this->logger = $logger;
    }

    /**
     * @Route("/saveMessages", name="save-messages", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function saveMessages(Request $request): Response
    {
        $data = $this->checkData(['chatbotMessages'], [], $request);
        $userId = $this->getUser()->getId();

        try {
            $this->chatbotMessagesService->saveFromRequest($data, $userId);
        } catch (Throwable $e) {
            dd($e);
            $this->logger->error($e->getMessage(), $e->getTrace());

            return $this->json(['status' => 'error']);
        }

        return $this->json(['status' => 'ok']);
    }
}
