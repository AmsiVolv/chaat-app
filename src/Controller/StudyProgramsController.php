<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\OpenDaysService;
use App\Services\StudyProgramService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/studyPrograms", name="study-programs")
 * Class StudyProgramsController
 * @package App\Controller
 */
class StudyProgramsController extends AbstractController
{
    private StudyProgramService $studyProgramService;
    private LoggerInterface $logger;

    use CheckRequestDataTrait;

    public function __construct(StudyProgramService $studyProgramService, LoggerInterface $logger)
    {
        $this->studyProgramService = $studyProgramService;
        $this->logger = $logger;
    }

    /**
     * @Route("/get", name="get-study-programs", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getOpenDays(Request $request): Response
    {
        try {
            $studyPrograms = $this->studyProgramService->getAll();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($studyPrograms);
    }
}
