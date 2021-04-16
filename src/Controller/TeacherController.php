<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\TeacherService;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/teachers", name="teachers")
 * Class TeacherController
 * @package App\Controller
 */
class TeacherController extends AbstractController
{
    private LoggerInterface $logger;
    private TeacherService $teacherService;
    use CheckRequestDataTrait;

    public function __construct(LoggerInterface $logger, TeacherService $teacherService)
    {
        $this->logger = $logger;
        $this->teacherService = $teacherService;
    }

    /**
     * @Route("/getTeacherByName", name="get-teacher-by-name", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getTeacherByName(Request $request): Response
    {
        try {
            $data = $this->checkData(['teacher'], [], $request);

            $teacher = $this->teacherService->getByTeacherName($data->teacher);
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($teacher);
    }

    /**
     * @Route("/getTeacherInfoById", name="get-teacher-info-by-id", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getTeacherInfoById(Request $request): Response
    {
        try {
            $data = $this->checkData(['teacher'], [], $request);

            $teacher = $this->teacherService->getByTeacherId($data->teacher);
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            dd($e);
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($teacher);
    }
}
