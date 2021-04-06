<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\OpenDays;
use App\Repository\OpenDaysRepository;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * Class OpenDaysService
 * @package App\Services
 */
class OpenDaysService
{
    private const REQUEST_URI = 'https://www.vse.cz/zajemci-o-studium/bakalarske-obory/dny-otevrenych-dveri/';
    private const BASE_URI = 'https://www.vse.cz';

    private OpenDaysRepository $openDaysRepository;
    private Client $client;

    public function __construct(OpenDaysRepository $openDaysRepository)
    {
        $this->openDaysRepository = $openDaysRepository;
        $this->client = new Client();
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function getOpenDays(): array
    {
        if ($this->todayChecked()) {
            $htmlResponse = $this->client->get(self::REQUEST_URI)->getBody()->getContents();
            $crawler = new Crawler($htmlResponse, self::REQUEST_URI, self::BASE_URI);
            $i = 0;

            foreach ($crawler->filter('.table-striped td') as $node) {
                foreach ($node->childNodes as $childNode) {
                    if ($i % 2) {
                        if (isset($openDay)) {
                            $openDaysDate = preg_replace('/\n/', '', $childNode->textContent);
                            $openDay->setOpenDayDate($openDaysDate);

                            try {
                                $link = $link = $crawler->selectLink($openDaysDate)->link()->getUri();
                                if ($link) {
                                    $openDay->setLink($link);
                                }
                            } catch (Throwable $e) {
                            }
                        }
                    } else {
                        $openDaysDescription = $node->textContent;
                        $openDay = (new OpenDays())->setOpenDaysDescription($openDaysDescription);
                    }

                    if (isset($openDay) && $openDay->getOpenDayDate() && $openDay->getOpenDaysDescription()) {
                        $this->openDaysRepository->store($openDay->setTodayChecked(true));
                    }
                    $i++;
                }
            }
        }

        return $this->openDaysRepository->getByDate();
    }

    private function todayChecked(): bool
    {
        return $this->openDaysRepository->getByDate() === [];
    }
}
