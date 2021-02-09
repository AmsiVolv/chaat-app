<?php
declare(strict_types=1);

namespace App\AppBundle\Routing;

use CoolCredit\ServiceBundle\Service\PageService;
use CoolCredit\ServiceBundle\Service\PageTagService;
use CoolCredit\ServiceBundle\Service\PageUrlHistoryService;
use Exception;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PageRouter
 * @package FrontendBundle\Routing
 */
class PageRouter implements RouteProviderInterface
{
    private PageService $pageService;
    private PageTagService $pageTagService;
    private RouteCollection $collectionRequest;
    private PageUrlHistoryService $pageUrlHistoryService;
    private array $routes = [];

    public function __construct(
        PageService $pageService,
        PageTagService $pageTagService,
        PageUrlHistoryService $pageUrlHistoryService
    ) {
        $this->pageService = $pageService;
        $this->pageTagService = $pageTagService;
        $this->pageUrlHistoryService = $pageUrlHistoryService;
        $this->collectionRequest = new RouteCollection();
    }

    /**
     * @param Request $request
     * @return RouteCollection
     * @throws Exception
     */
    public function getRouteCollectionForRequest(Request $request): RouteCollection
    {
        $this->getRouteCollectionForRequestPages();

        return $this->collectionRequest;
    }

    /**
     * @param string $name
     * @return Route
     * @throws Exception
     */
    public function getRouteByName($name): Route
    {
        if (strpos($name, 'frontend-page-list-') === 0) {
            return $this->getRouteByNameList($name);
        } elseif (strpos($name, 'frontend-page-redirect-') === 0) {
            return $this->getRouteByNameRedirect($name);
        } elseif (strpos($name, 'frontend-page-') === 0) {
            return $this->getRouteByNamePages($name);
        } else {
            throw new RouteNotFoundException("No route found for name '$name'");
        }
    }

    /**
     * @param array|null $names
     * @return Route[]
     * @throws Exception
     */
    public function getRoutesByNames($names): array
    {
        $this->getRoutesByNamesPages();
        $this->getRoutesByNamesRedirect();

        return $this->routes;
    }

    /**
     * @throws Exception
     */
    private function getRouteCollectionForRequestPages(): void
    {
        $pages = $this->pageService->getAllVisible();

        if ($pages !== []) {
            foreach ($pages as $page) {
                $defaults = [
                    '_controller' => 'FrontendBundle:Page:detail',
                    'id' => $page->getId(),
                ];
                $requirements = [
                    'parameter' => '\d+',
                ];
                $route = new Route($page->getUrl(), $defaults, $requirements);

                $this->collectionRequest->add('frontend-page-' . $page->getId(), $route);
            }
        }

        $pageTags = $this->pageTagService->getAllVisible();

        if ($pageTags !== []) {
            foreach ($pageTags as $tag) {
                if ($tag->getPages() != []) {
                    $defaults = [
                        '_controller' => 'FrontendBundle:Page:list',
                        'id' => $tag->getId(),
                    ];
                    $requirements = [
                        'parameter' => '\d+',
                    ];
                    $route = new Route($tag->getUrl(), $defaults, $requirements);

                    $this->collectionRequest->add('frontend-page-list-' . $tag->getId(), $route);
                }
            }
        }

        $pageUrlsHistory = $this->pageUrlHistoryService->getAll();

        if ($pageUrlsHistory !== []) {
            foreach ($pageUrlsHistory as $page) {
                $defaults = [
                    '_controller' => 'FrontendBundle:Page:redirect',
                    'id' => $page->getPageId(),
                ];
                $requirements = [
                    'parameter' => '\d+',
                ];
                $route = new Route($page->getUrl(), $defaults, $requirements);

                $this->collectionRequest->add('frontend-page-redirect-' . $page->getId(), $route);
            }
        }
    }

    /**
     * @param string $name
     * @return Route
     * @throws Exception
     */
    private function getRouteByNamePages(string $name): Route
    {
        $id = (int)str_replace('frontend-page-', '', $name);
        $page = $this->pageService->getById($id);

        if ($page == null) {
            throw new RouteNotFoundException("No route found for name '$name'");
        }

        return new Route($page->getUrl(), ['id' => $page->getId()], [], []);
    }

    /**
     * @param string $name
     * @return Route
     * @throws RouteNotFoundException
     */
    private function getRouteByNameList(string $name): Route
    {
        $id = (int)str_replace('frontend-page-list-', '', $name);
        $page = $this->pageTagService->get($id);

        if ($page == null) {
            throw new RouteNotFoundException("No route found for name '$name'");
        }

        return new Route($page->getUrl(), ['id' => $page->getId()], [], []);
    }

    /**
     * @param string $name
     * @return Route
     * @throws RouteNotFoundException
     */
    private function getRouteByNameRedirect(string $name): Route
    {
        $id = (int)str_replace('frontend-page-redirect-', '', $name);
        $pageHistory = $this->pageUrlHistoryService->getAll();

        if (count($pageHistory) === 0) {
            throw new RouteNotFoundException("No route found for name '$name'");
        }

        foreach ($pageHistory as $page) {
            if ($page->getId() !== $id) {
                throw new RouteNotFoundException("No route found for name '$name'");
            }

            return new Route($page->getUrl(), ['id' => $page->getId()], [], []);
        }
    }

    /**
     * @throws Exception
     */
    private function getRoutesByNamesPages(): void
    {
        $pages = $this->pageService->getAllVisible();

        if ($pages === []) {
            throw new RouteNotFoundException("No route found for name");
        }

        foreach ($pages as $page) {
            $defaults = [
                '_controller' => 'FrontendBundle:Page:detail',
                'id' => $page->getId(),
            ];
            $requirements = [
                'parameter' => '\d+',
            ];

            $this->routes['frontend-page-' . $page->getId()] = new Route(
                $page->getUrl(),
                $defaults,
                $requirements
            );
        }
    }

    private function getRoutesByNamesRedirect(): void
    {
        $historyPage = $this->pageUrlHistoryService->getAll();

        if (count($historyPage) === 0) {
            throw new RouteNotFoundException("No route found for name");
        }

        foreach ($historyPage as $page) {
            $defaults = [
                '_controller' => 'FrontendBundle:Page:redirect',
                'id' => $page->getPageId(),
            ];
            $requirements = [
                'parameter' => '\d+',
            ];

            $this->routes['frontend-page-redirect-' . $page->getId()] = new Route(
                $page->getUrl(),
                $defaults,
                $requirements
            );
        }
    }
}
