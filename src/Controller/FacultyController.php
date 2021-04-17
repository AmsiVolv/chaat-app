<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\FacultyService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/faculty", name="faculty")
 * Class FacultyController
 * @package App\Controller
 */
class FacultyController extends AbstractController
{
    private FacultyService $facultyService;
    private LoggerInterface $logger;

    use CheckRequestDataTrait;

    public function __construct(FacultyService $facultyService, LoggerInterface $logger)
    {
        $this->facultyService = $facultyService;
        $this->logger = $logger;
    }

    /**
     * @Route("/getAll", name="get-all", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllFaculties(Request $request): Response
    {
        $data = [];

        try {
            $data = $this->facultyService->getAll();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($data);
    }

    /**
     * @Route("/get", name="get-faculty", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getOpenDays(Request $request): Response
    {
        try {
            $openDays = $this->facultyService->getAllStudyAdmission();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($openDays);
    }
}
