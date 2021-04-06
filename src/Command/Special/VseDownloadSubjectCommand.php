<?php
declare(strict_types=1);

namespace App\Command\Special;

use App\Entity\Course;
use App\Entity\Faculty;
use App\Repository\CourseRepository;
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
 * Class VseDownloadSubjectCommand
 * @package App\Command\Special
 */
class VseDownloadSubjectCommand extends Command
{
    private const DESCRIPTION = 'Download info from INSIS';
    private const BASE_URI = 'https://insis.vse.cz';
    /** @var string */
    protected static $defaultName = 'vse:download:subject';
    private SymfonyStyle $io;
    private LoggerInterface $logger;
    private Client $client;
    private CssSelectorConverter $cssSelector;
    private FacultyRepository $facultyRepository;
    private CourseRepository $courseRepository;

    public function __construct(
        LoggerInterface $logger,
        FacultyRepository $facultyRepository,
        CourseRepository $courseRepository
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
        $this->courseRepository = $courseRepository;
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
        $faculties = $this->facultyRepository->getAll();

        foreach ($faculties as $faculty) {
            $currentUri = $faculty->getPeriod();
            if ($currentUri) {
                $response = $this->client->get($currentUri);
                $htmlResponse = $response->getBody()->getContents();
                try {
                    $courseCount = 0;
                    $crawler = new Crawler($htmlResponse, $currentUri, self::BASE_URI);
                    $tableNode = $crawler->filter('#tmtab_1')->filter('tbody')->filter('tr');
                    foreach ($tableNode as $node) {
                        $i = 0;
                        $courseCode = '';
                        $courseTitle = '';
                        $link = null;

                        foreach ($node->childNodes as $childNode) {
                            if ($i === 0) {
                                $courseCode = $childNode->textContent;
                            }

                            if ($i === 1) {
                                $courseTitle = $childNode->textContent;
                                $link = $crawler->selectLink($childNode->textContent)->link()->getUri();
                                $link = preg_replace('/;zpet=.*/', '', $link);

                                $course = (new Course(
                                    $courseCode,
                                    $courseTitle,
                                    $link
                                ))->setFaculty($faculty);
                                $this->courseRepository->store($course);
                                $courseCount++;
                            }
                            $i++;
                        }
                    }
                    $this->io->success(sprintf('%d courses was imported for %s', $courseCount, $faculty->getFacultyName()));
                } catch (Throwable $e) {
                    $this->logger->error($e->getMessage(), $e->getTrace());
                }
            }
        }

        $this->io->success('Download was completed!');

        return 0;
    }
}
