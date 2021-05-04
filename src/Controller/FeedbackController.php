<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\FeedbackService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/feedback", name="feedback")
 * Class FeedbackController
 * @package App\Controller
 */
class FeedbackController extends AbstractController
{
    use CheckRequestDataTrait;

    private LoggerInterface $logger;
    private FeedbackService $feedbackService;

    public function __construct(LoggerInterface $logger, FeedbackService $feedbackService)
    {
        $this->logger = $logger;
        $this->feedbackService = $feedbackService;
    }

    /**
     * @Route("/save", name="save-feedback", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function saveMessages(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $data = $this->checkData(['feedback'], [], $request);

        try {
            $feedback = $this->feedbackService->saveFeedbackFromRequest($data, $userId);
        } catch (Throwable $e) {
            dd($e);
            $this->logger->error($e->getMessage(), $e->getTrace());

            return $this->json(['status' => 'error']);
        }

        return $this->json(['status' => 'success']);
    }
}
