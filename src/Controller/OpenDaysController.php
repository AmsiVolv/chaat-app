<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\OpenDaysService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/openDays", name="open-days")
 * Class OpenDaysController
 * @package App\Controller
 */
class OpenDaysController extends AbstractController
{
    private OpenDaysService $openDaysService;
    private LoggerInterface $logger;

    use CheckRequestDataTrait;

    public function __construct(OpenDaysService $openDaysService, LoggerInterface $logger)
    {
        $this->openDaysService = $openDaysService;
        $this->logger = $logger;
    }

    /**
     * @Route("/get", name="get-open-days", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getOpenDays(Request $request): Response
    {
        try {
            $openDays = $this->openDaysService->getOpenDays();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($openDays);
    }
}
