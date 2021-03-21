<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\CourseService;
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

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
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
}
