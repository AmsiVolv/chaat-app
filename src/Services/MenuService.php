<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Menu;
use App\Entity\MenuMeals;
use App\Entity\SchoolArea;
use App\Repository\MenuMealsRepository;
use App\Repository\MenuRepository;
use App\Repository\SchoolAreaRepository;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuService
{
    private const BASE_URI = 'https://www.vse.cz';

    private MenuRepository $menuRepository;
    private MenuMealsRepository $menuMealsRepository;
    private SchoolAreaRepository $schoolArea;
    private Client $client;

    public function __construct(
        MenuRepository $menuRepository,
        SchoolAreaRepository $schoolArea,
        MenuMealsRepository $menuMealsRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->schoolArea = $schoolArea;
        $this->menuMealsRepository = $menuMealsRepository;
        $this->client = new Client();
    }

    /**
     * @param string $areal
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMenuByArea(string $areal): array
    {
        $menu = [];
        $area = $this->schoolArea->getByArelTitle($areal);

        if ($area) {
            $this->checkForUpdate($area);
            $menu = $this->menuRepository->getMenuByArea($area);
        }

        return $menu;
    }

    /**
     * @param SchoolArea $area
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkForUpdate(SchoolArea $area): void
    {
        $htmlResponse = $this->client->get($area->getAreaMenuLink())->getBody()->getContents();
        $crawler = new Crawler($htmlResponse, $area->getAreaMenuLink(), self::BASE_URI);
        if ($this->menuRepository->getByDate($area) === []) {
            $menu = new Menu($area);
            $menu = $this->menuRepository->store($menu);
        } else {
            return;
        }

        if ($area->getId() === SchoolAreaRepository::ZIZKOV_ID) {
            $tableId = '#avgastro-zizkov-today';
        } else {
            return;
            // DOCASNE nefuguje
//            $tableId = '';
        }

        $meals = $crawler->filter($tableId)->filter('tbody')->first()->filter('tr')->each(function ($node) {
            $mealName = $node->children('th')->text();
            $mealContent = $node->children('td')->text();

            return compact('mealName', 'mealContent');
        });

        foreach ($meals as $meal) {
            $this->menuMealsRepository->store(new MenuMeals(
                $meal['mealName'],
                $meal['mealContent'],
                $menu
            ));
        }
    }
}
