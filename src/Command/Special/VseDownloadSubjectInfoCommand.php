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
 * Class VseDownloadSubjectInfoCommand
 * @package App\Command\Special
 */
class VseDownloadSubjectInfoCommand extends Command
{
    private const DESCRIPTION = 'Download subject info from INSIS';
    private const BASE_URI = 'https://insis.vse.cz';
    private const QUERY_STRING = ';odkud=;zobrazit_sklad=0;zobrazit_obdobi=0;obdobi=;predmet=160494;typ=1;jazyk=1;vystup=1';
    private const TEACHER_PROPERTY = 'Vyučující';
    private const SCHEDULING_NODE = 'Periodické rozvrhové akce: ';
    private const TEACHER_HREF_QUERY = '/lide/clovek.pl?id=';
    private const LIBRARY_HREF_QUERY = 'https://library.vse.cz/sysno?';

    /** @var string */
    protected static $defaultName = 'vse:download:subjectInfo';
    private SymfonyStyle $io;
    private LoggerInterface $logger;
    private Client $client;
    private CssSelectorConverter $cssSelector;
    private FacultyRepository $facultyRepository;
    private CourseRepository $courseRepository;
    private CourseShedulingRepository $courseSchedulingRepository;
    private ReadingRepository $readingRepository;
    private TeacherRepository $teacherRepository;

    private array $keysToSearch = [
        'Počet přidělených ECTS kreditů' => 'creditCount',
        'Jazyk výuky' => 'courseLanguage',
        'Doporučený typ a ročník studia' => 'courseLevelAndYearOfStudy',
        'Obsah předmětu' => 'courseContent',
        'Zaměření předmětu' => 'courseAims',
        'Doporučené doplňky kurzu' => 'courseRecommendation',
    ];

    public function __construct(
        LoggerInterface $logger,
        FacultyRepository $facultyRepository,
        CourseRepository $courseRepository,
        CourseShedulingRepository $courseSchedulingRepository,
        ReadingRepository $readingRepository,
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
        $this->cssSelector = new CssSelectorConverter();
        $this->facultyRepository = $facultyRepository;
        $this->courseRepository = $courseRepository;
        $this->courseSchedulingRepository = $courseSchedulingRepository;
        $this->readingRepository = $readingRepository;
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
        $courses = $this->courseRepository->getAll();
        $coursesUpdated = 0;
        foreach ($courses as $course) {
            $currentUrl = $course->getCourseUrl();

            if ($currentUrl) {
                $response = $this->client->get(sprintf('%s%s', $currentUrl, self::QUERY_STRING));
                $htmlResponse = $response->getBody()->getContents();

                try {
                    $crawler = new Crawler($htmlResponse, $currentUrl, self::BASE_URI);
                    $tableNode = $crawler->filter('table')->filter('tbody');

                    $course = $this->processNodeForScheduling($crawler, $course);
                    $course = $this->processNodeForTeachers($crawler, $course);
                    $course = $this->processNodeForLibrary($crawler, $course);

                    foreach ($tableNode->children() as $node) {
                        $course = $this->processNode($node, $course);
                    }

                    $this->courseRepository->store($course);
                    $this->io->success(sprintf('Course [%d] was updated, remain [%d]', $course->getId(), count($courses) - $coursesUpdated));
                    $coursesUpdated++;
                } catch (Throwable $e) {
                    $this->io->warning(sprintf('Course [%d] was not updated', $course->getId()));
                    $this->logger->error($e->getMessage(), $e->getTrace());
                }
            }
        }

        $this->io->success(sprintf('Download was completed! Courses [%d] were updated', $coursesUpdated));

        return 0;
    }

    /**
     * @param Crawler $crawler
     * @param Course $course
     * @return Course
     * @throws Throwable
     */
    private function processNodeForScheduling(Crawler $crawler, Course $course): Course
    {
        $node = $crawler->filter('span');
        foreach ($node as $item) {
            if ($item->textContent === self::SCHEDULING_NODE) {
                $schedulingNode = $item->parentNode->parentNode->parentNode->parentNode->nextSibling;
                foreach ($schedulingNode->childNodes as $childNode) {
                    foreach ($childNode->firstChild->lastChild->lastChild->childNodes as $schedulingItem) {
                        $i=0;
                        $courseScheduling = (new CourseSheduling())->setCourse($course);
                        foreach ($schedulingItem->childNodes as $item) {
                            switch ($i) {
                                case 0:
                                    $courseScheduling->setDay($item->textContent);
                                    break;
                                case 1:
                                    $courseScheduling->setTime($item->textContent);
                                    break;
                                case 2:
                                    $courseScheduling->setRoom($item->textContent);
                                    break;
                                case 3:
                                    $courseScheduling->setEventType($item->textContent);
                                    break;
                                case 6:
                                    $courseScheduling->setCapacity((int) $item->textContent);
                                    break;
                            }
                            $i++;
                        }
                        if ($courseScheduling->getDay() !== 'Nenalezena žádná vyhovující data.') {
                            $course->addCourseSheduling($courseScheduling);
                            $this->courseSchedulingRepository->store($courseScheduling);
                        }
                    }
                }
            }
        }

        return $course;
    }

    /**
     * @param Crawler $crawler
     * @param Course $course
     * @return Course
     * @throws Throwable
     */
    private function processNodeForTeachers(Crawler $crawler, Course $course): Course
    {
        $links = $crawler->filter('a')->links();
        foreach ($links as $link) {
            if (str_contains($link->getUri(), self::TEACHER_HREF_QUERY)) {
                $previousSibling = $link->getNode()->parentNode->parentNode->previousSibling;
                if ($previousSibling) {
                    $previousSibling = $previousSibling->textContent;
                    $previousSibling = trim(str_replace(':', '', $previousSibling));
                    if ($previousSibling === self::TEACHER_PROPERTY) {
                        $teacher = new Teacher(
                            $link->getNode()->textContent,
                            $link->getUri()
                        );

                        if ($teacher->getTeacherUri()) {
                            $teacherFromRepository = $this->teacherRepository->getByUri($teacher->getTeacherUri());
                            if ($teacherFromRepository) {
                                $teacher = $teacherFromRepository;
                            }

                            $course->addTeacher($teacher);
                            $teacher->addCourse($course);
                            $this->teacherRepository->store($teacher);
                        }
                    }
                }
            }
        }

        return $course;
    }

    /**
     * @param Crawler $crawler
     * @param Course $course
     * @return Course
     * @throws Throwable
     */
    private function processNodeForLibrary(Crawler $crawler, Course $course): Course
    {
        $links = $crawler->filter('a')->links();
        foreach ($links as $link) {
            if (str_contains($link->getUri(), self::LIBRARY_HREF_QUERY)) {
                $previousSibling = $link->getNode()->parentNode->parentNode->previousSibling;
                $i = 0;
                $reading = (new Reading())->addCurse($course);
                foreach ($previousSibling->parentNode->childNodes as $childNode) {
                    switch ($i) {
                        case 0:
                            $reading->setReadingType($childNode->textContent);
                            break;
                        case 1:
                            $reading->setAuthor($childNode->textContent);
                            break;
                        case 2:
                            $reading->setTitle($childNode->textContent);
                            break;
                        case 6:
                            $reading->setISBN($childNode->textContent);
                            break;
                        case 7:
                            $reading->setLibraryLink($isbn = $link->getUri());
                            break;
                    }
                    $i++;
                }
                if ($reading->getISBN()) {
                    $bookFromRepository = $this->readingRepository->getByIsbn($reading->getISBN());

                    if ($bookFromRepository) {
                        $reading = $bookFromRepository;
                    }

                    $course->addReading($reading);
                    $this->readingRepository->store($reading);
                }
            }
        }

        return $course;
    }

    private function processNode(DOMElement $element, Course $course): Course
    {
        $stringsArray = explode(':', $element->textContent);
        $readyForSet = false;

        foreach ($stringsArray as $key => $item) {
            if ($key === 0) {
                $readyForSet = $this->nodeValueInArray($item);
            }

            if ($key === 1 && $readyForSet && $item !== ' ') {
                $setter = $this->keysToSearch[$stringsArray[0]];
                $setter = sprintf('set%s', ucfirst($setter));
                $course->$setter($item);
            }

            if ($key === 1 && $readyForSet && $item === ' ') {
                $setter = $this->keysToSearch[$stringsArray[0]];
                $setter = sprintf('set%s', ucfirst($setter));
                $item = $element->nextSibling->textContent;
                $course->$setter($item);
            }
        }

        return $course;
    }

    private function nodeValueInArray(string $value): bool
    {
        return array_key_exists($value, $this->keysToSearch);
    }
}
