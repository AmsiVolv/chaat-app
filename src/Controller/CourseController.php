<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\CourseService;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/course", name="messages")
 * Class CourseController
 * @package App\Controller
 */
class CourseController extends AbstractController
{
    use CheckRequestDataTrait;

    private CourseService $courseService;
    private LoggerInterface $logger;

    public function __construct(CourseService $courseService, LoggerInterface $logger)
    {
        $this->courseService = $courseService;
        $this->logger = $logger;
    }

    /**
     * @Route("/get", name="get-course", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getCourse(Request $request): Response
    {
        $data = $this->checkData([], ['course', 'filerParams'], $request);

        $course = $this->courseService->getCourseInfo($data);

        return $this->json($course);
    }

    /**
     * @Route("/getAll", name="get-all", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getAllCourse(Request $request): Response
    {
        $courses = $this->courseService->getAllCursesCodes();

        return $this->json($courses);
    }

    /**
     * @Route("/getAllByParam", name="get-all-by-param", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getAllCourseByParam(Request $request): Response
    {
        try {
            $data = $this->checkData(['getAll'], [], $request);

            $courses = $this->courseService->getAllCourseByParam($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            dd($e);
            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($courses);
    }
}
