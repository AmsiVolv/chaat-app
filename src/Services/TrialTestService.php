<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\TrialTest;
use App\Repository\TrialTestRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * Class TrialTestService
 * @package App\Services
 */
class TrialTestService
{
    private const REQUEST_URI = 'https://www.vse.cz/zajemci-o-studium/bakalarske-obory/prijimaci-rizeni-a-prihlaska-ke-studiu/specifikace-obsahu-prijimacich-zkousek/zkusebni-testy-z-minulych-let/';
    private const BASE_URI = 'https://www.vse.cz';

    private TrialTestRepository $trialTestRepository;
    private Client $client;

    public function __construct(TrialTestRepository $trialTestRepository)
    {
        $this->trialTestRepository = $trialTestRepository;
        $this->client = new Client();
    }


    /**
     * @return array
     * @throws GuzzleException
     * @throws Throwable
     */
    public function getAll(): array
    {
        $this->updateChecker();

        return $this->trialTestRepository->getAllForResponse();
    }

    /**
     * @throws Throwable
     * @throws GuzzleException
     */
    public function updateChecker(): void
    {
        $htmlResponse = $this->client->get(self::REQUEST_URI)->getBody()->getContents();
        $crawler = new Crawler($htmlResponse, self::REQUEST_URI, self::BASE_URI);
        $links = $crawler->filter('a')->each(function ($node) {
            $href  = $node->attr('href');
            $title = $node->attr('title');
            $text  = $node->text();

            return compact('href', 'title', 'text');
        });

        foreach ($links as $link) {
            if (str_contains($link['href'], 'https://www.vse.cz/wp-content/uploads/page/') && $link['text']) {
                $text = preg_replace('/[0-9]*,[0-9]*.*/', '', $link['text']);
                $keyword = explode(' ', $text)[0];

                if ($this->trialTestRepository->getByLink($link['href']) === []) {
                    $trialTest = new TrialTest(
                        $text,
                        $link['href'],
                        $keyword
                    );

                    $this->trialTestRepository->store($trialTest);
                }
            }
        }
    }
}
