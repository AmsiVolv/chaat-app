<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\TrialTestRepository;
use App\Services\TrialTestService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/trialTest", name="trial-tests")
 * Class TrialTestController
 * @package App\Controller
 */
class TrialTestController extends AbstractController
{
    private TrialTestService $trialTestService;
    private LoggerInterface $logger;

    public function __construct(TrialTestService $trialTestService, LoggerInterface $logger)
    {
        $this->trialTestService = $trialTestService;
        $this->logger = $logger;
    }

    /**
     * @Route("/get", name="get-trial-tests", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getOpenDays(Request $request): Response
    {
        try {
            $openDays = $this->trialTestService->getAll();
        } catch (Throwable $e) {
            dd($e);
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($openDays);
    }
}
