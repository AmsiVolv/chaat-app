<?php
declare(strict_types=1);

namespace App\Command\Special;

use App\Entity\Course;
use App\Entity\CourseSheduling;
use App\Entity\Reading;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\CourseShedulingRepository;
use App\Repository\FacultyRepository;
use App\Repository\ReadingRepository;
use App\Repository\TeacherRepository;
use DOMElement;
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
 * Class VseDownloadTeacherInfoCommand
 * @package App\Command\Special
 */
class VseDownloadTeacherInfoCommand extends Command
{
    private const DESCRIPTION = 'Download teacher info from INSIS';
    private const BASE_URI = 'https://insis.vse.cz';
    private const QUERY_STRING = ';odkud=;zobrazit_sklad=0;zobrazit_obdobi=0;obdobi=;predmet=160494;typ=1;jazyk=1;vystup=1';

    /** @var string */
    protected static $defaultName = 'vse:download:teachers';
    private SymfonyStyle $io;
    private LoggerInterface $logger;
    private Client $client;
    private TeacherRepository $teacherRepository;

    private array $keysToSearch = [
        'Office phone number' => 'phoneNumber',
        'Office number' => 'officeNumber',
        'E-mail' => 'email',
    ];

    public function __construct(
        LoggerInterface $logger,
        TeacherRepository $teacherRepository,
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
        $this->teacherRepository = $teacherRepository;
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
        $teachers = $this->teacherRepository->getAll();
        $teachersUpdated = 0;
        foreach ($teachers as $teacher) {
            $currentUrl = $teacher->getTeacherUri();

            if ($currentUrl) {
                $currentUrl = str_replace('/lide/', '/auth/lide/', $currentUrl);
                $response = $this->client->get(sprintf('%s%s', $currentUrl, self::QUERY_STRING));
                $htmlResponse = $response->getBody()->getContents();

                try {
                    $crawler = new Crawler($htmlResponse, $currentUrl, self::BASE_URI);

                    $infoNode = $crawler->filter('table')->getNode(3);

                    foreach ($infoNode->childNodes as $nodeItem) {
                        /** @var DOMElement $childNode */
                        foreach ($nodeItem->childNodes as $childNode) {
                            $this->processNode($childNode, $teacher);
                        }
                    }

                    $teacher = $this->prepareTeacherForStore($teacher);
                    $this->teacherRepository->store($teacher);
                    $teachersUpdated++;
                    $this->io->success(
                        sprintf(
                            'Teacher [%d] was updated, remain [%d]',
                            $teacher->getId(),
                            count($teachers) - $teachersUpdated
                        )
                    );
                } catch (Throwable $e) {
                    $this->io->warning(sprintf('Course [%d] was not updated', $teacher->getId()));
                    $this->logger->error($e->getMessage(), $e->getTrace());
                }
            }
        }

        $this->io->success(sprintf('Download was completed! Courses [%d] were updated', $teachersUpdated));

        return 0;
    }

    private function processNode(DOMElement $element, Teacher $teacher): Teacher
    {
        $stringsArray = explode(':', $element->textContent);
        $readyForSet = false;

        foreach ($stringsArray as $key => $item) {
            if ($key === 0) {
                $readyForSet = $this->nodeValueInArray($item);
            }

            if ($key === 1 && $readyForSet && $item) {
                $setter = $this->keysToSearch[$stringsArray[0]];
                $setter = sprintf('set%s', ucfirst($setter));
                $teacher->$setter($item);
            }
        }

        return $teacher;
    }

    private function prepareTeacherForStore(Teacher $teacher): Teacher
    {
        if ($teacher->getEmail()) {
            $teacher->setEmail(
                str_replace(' [at] ', '@', $teacher->getEmail())
            );
        }

        if ($teacher->getTeacherUri()) {
            $teacher->setExternalId(
                str_replace('https://insis.vse.cz/lide/clovek.pl?id=', '', $teacher->getTeacherUri())
            );
        }

        return $teacher;
    }

    private function nodeValueInArray(string $value): bool
    {
        return array_key_exists($value, $this->keysToSearch);
    }
}
