<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\PreparatoryCourseService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/preparatoryCourse", name="preparatory-course")
 * Class PreparatoryCourseController
 * @package App\Controller
 */
class PreparatoryCourseController extends AbstractController
{
    private PreparatoryCourseService $preparatoryCourseService;
    private LoggerInterface $logger;

    use CheckRequestDataTrait;

    public function __construct(PreparatoryCourseService $preparatoryCourseService, LoggerInterface $logger)
    {
        $this->preparatoryCourseService = $preparatoryCourseService;
        $this->logger = $logger;
    }

    /**
     * @Route("/get", name="get-preparatory-course", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getPreparatoryCourses(Request $request): Response
    {
        try {
            $preparatoryCourses = $this->preparatoryCourseService->getAll();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($preparatoryCourses);
    }
}
