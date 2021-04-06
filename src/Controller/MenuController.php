<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\MenuService;
use App\Services\OpenDaysService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/menu", name="menu")
 * Class MenuController
 * @package App\Controller
 */
class MenuController extends AbstractController
{
    private MenuService $menuService;
    private LoggerInterface $logger;

    use CheckRequestDataTrait;

    public function __construct(MenuService $menuService, LoggerInterface $logger)
    {
        $this->menuService = $menuService;
        $this->logger = $logger;
    }

    /**
     * @Route("/get", name="menu-get", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getOpenDays(Request $request): Response
    {
        try {
            $data = $this->checkData(['areal'], [], $request);
            $areal = (string) $data->areal;

            $menu = $this->menuService->getMenuByArea($areal);
        } catch (Throwable $e) {
            dd($e);
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new JsonResponse(['status' => 'Response error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($menu);
    }
}
