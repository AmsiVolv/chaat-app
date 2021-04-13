<?php
declare(strict_types=1);

namespace App\Command\Special;

use App\Entity\Faculty;
use App\Repository\FacultyRepository;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * Class VseDownloadFacultyCommand
 * @package App\Command\Special
 */
class VseDownloadFacultyCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'vse:download:faculty';
    private const DESCRIPTION = 'Download info from INSIS';
    private const BASE_URI = 'https://insis.vse.cz';
    private const CURRENT_URI = 'https://insis.vse.cz/auth/student/hodnoceni.pl?_m=3167;lang=cz';

    private SymfonyStyle $io;
    private LoggerInterface $logger;
    private Client $client;
    private CssSelectorConverter $cssSelector;
    private FacultyRepository $facultyRepository;

    public function __construct(
        LoggerInterface $logger,
        FacultyRepository $facultyRepository
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->client = new Client([
            'headers' => [
                'Connection' => 'keep-alive',
                'Cache-Control' => 'max-age=0',
                'sec-ch-ua' => '"Google Chrome";v="89", "Chromium";v="89", ";Not A Brand";v="99"',
                'sec-ch-ua-mobile' => '?0',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Sec-Fetch-Site' => 'same-origin',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-User' => '?1',
                'Sec-Fetch-Dest' => 'document',
                'Referer' => 'https://insis.vse.cz/auth/?lang=cz',
                'Accept-Language' => 'en-US,en;q=0.9,ru;q=0.8,cs;q=0.7',
                'Cookie' => '_ga=GA1.2.1722001478.1616053043; UISAuth=g%2FmRtm7AgX3uzMSRECm43QnjSgV%2BA3xya4BCWPlP98Zw; _gid=GA1.2.883744495.1616232669; _gat_gtag_UA_8293979_5=1',
            ],
        ]);
        $this->cssSelector = new CssSelectorConverter();
        $this->facultyRepository = $facultyRepository;
    }

    protected function configure(): void
    {
        $this->setName(self::$defaultName)
            ->setDescription(self::DESCRIPTION)
            ->setHelp(self::DESCRIPTION);
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(self::DESCRIPTION);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $response = $this->client->get(self::CURRENT_URI);
        $htmlResponse = $response->getBody()->getContents();
        $crawler = new Crawler($htmlResponse, self::CURRENT_URI, self::BASE_URI);
        foreach ($crawler->filter('.odsazena') as $node) {
            if ($node->attributes->count() === 4) {
                $textContent = $node->textContent;
                $imgLink = $crawler->filter('.odsazena span a')->filter('img')->attr('src');
                $link = $crawler->selectLink($node->textContent)->link()->getUri();

                if (preg_match_all('/\b(\w)/', strtoupper($textContent), $m)) {
                    $abbreviation = implode('', $m[1]);
                }

                try {
                    $faculty = new Faculty(
                        $textContent,
                        $imgLink,
                        $link,
                        $abbreviation
                    );

                    $this->facultyRepository->store($faculty);
                } catch (Throwable $e) {
                    $this->logger->error($e->getMessage(), $e->getTrace());
                }
            }
        }

        return 0;
    }
}
