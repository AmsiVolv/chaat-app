<?php
declare(strict_types=1);

namespace App\Command\Special;

use App\Entity\Faculty;
use App\Entity\StudyProgram;
use App\Entity\StudyProgramAim;
use App\Entity\StudyProgramFormType;
use App\Entity\StudyProgramLanguage;
use App\Repository\FacultyRepository;
use App\Repository\StudyProgramAimRepository;
use App\Repository\StudyProgramFormTypeRepository;
use App\Repository\StudyProgramLanguageRepository;
use App\Repository\StudyProgramRepository;
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
 * Class VseProcessStudyPrograms
 * @package App\Command\Special
 */
class VseProcessStudyPrograms extends Command
{
    /** @var string */
    protected static $defaultName = 'vse:s:p';

    private const DESCRIPTION = 'Process study programs';
    private const REQUEST_URI = 'https://www.vse.cz/zajemci-o-studium/bakalarske-obory/nabidka-studia/';

    private SymfonyStyle $io;
    private LoggerInterface $logger;
    private Client $client;

    private StudyProgramLanguageRepository $studyProgramLanguageRepository;
    private StudyProgramAimRepository $studyProgramAimRepository;
    private StudyProgramRepository $studyProgramRepository;
    private StudyProgramFormTypeRepository $studyProgramFormTypeRepository;

    public function __construct(
        LoggerInterface $logger,
        StudyProgramLanguageRepository $studyProgramLanguageRepository,
        StudyProgramAimRepository $studyProgramAimRepository,
        StudyProgramRepository $studyProgramRepository,
        StudyProgramFormTypeRepository $studyProgramFormTypeRepository
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->studyProgramLanguageRepository = $studyProgramLanguageRepository;
        $this->studyProgramAimRepository = $studyProgramAimRepository;
        $this->studyProgramRepository = $studyProgramRepository;
        $this->studyProgramFormTypeRepository = $studyProgramFormTypeRepository;
        $this->client = new Client();
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
        try {
            $response = $this->client->get(self::REQUEST_URI);
            $htmlResponse = $response->getBody()->getContents();
            $crawler = new Crawler($htmlResponse);

            foreach ($crawler->filter('script') as $node) {
                if ($node->textContent && str_contains($node->textContent, 'function ($)')) {
                    preg_match_all('/{.*}|\[.*\]/', trim($node->textContent), $matches);
                    foreach ($matches as $match) {
                        foreach ($match as $item) {
                            $item = json_decode($item);
                            if (is_array($item)) {
                                foreach ($item as $studyInformation) {
                                    if (preg_match('/obor[1-9]*/', $studyInformation) &&
                                        !$this->studyProgramRepository->getByExternalId($studyInformation)
                                    ) {
                                        $studyProgramNodes = $crawler
                                            ->filter(sprintf('#%s', $studyInformation))
                                            ->filter('.h5');
                                        foreach ($studyProgramNodes as $nodeChild) {
                                            $studyInformation = (new StudyProgram())
                                                ->setExternalId($studyInformation)
                                                ->setStudyProgramTitle(trim($nodeChild->textContent));

                                            $this->studyProgramRepository->store($studyInformation);
                                        }
                                    }
                                    if (in_array('čeština', $item)) {
                                        $language = trim($studyInformation);
                                        if (!$this->studyProgramLanguageRepository->getByLanguage($language)) {
                                            $studyInformationLanguage = new StudyProgramLanguage($language);

                                            $this->studyProgramLanguageRepository->store($studyInformationLanguage);
                                        }
                                    }
                                    if (in_array('Business', $item) || in_array('Marketing', $item)) {
                                        $programAim = trim($studyInformation);
                                        if (!$this->studyProgramAimRepository->getByName($programAim)) {
                                            $studyInformationAim = new StudyProgramAim($programAim);

                                            $this->studyProgramAimRepository->store($studyInformationAim);
                                        }
                                    }
                                    if (in_array('prezenční', $item) || in_array('kombinovaná', $item)) {
                                        $formType = trim($studyInformation);
                                        if (!$this->studyProgramFormTypeRepository->getByName($formType)) {
                                            $studyInformationFormType = new StudyProgramFormType($formType);

                                            $this->studyProgramFormTypeRepository->store($studyInformationFormType);
                                        }
                                    }
                                }
                            } else {
                                foreach ($item as $key => $value) {
                                    $studyProgram = $this->studyProgramRepository->getByExternalId($key);

                                    if (!is_array($value)) {
                                        $language = $this->studyProgramLanguageRepository->getByLanguage($value);
                                        $studyForm = $this->studyProgramFormTypeRepository->getByName($value);
                                        if ($language) {
                                            $studyProgram->setStudyProgramLanguage($language);
                                        } elseif ($studyForm) {
                                            $studyProgram->setStudyProgramForm($studyForm);
                                        }
                                    } else {
                                        foreach ($value as $studyAim) {
                                            $studyAim = $this->studyProgramAimRepository->getByName($studyAim);
                                            if ($studyAim) {
                                                $studyProgram->addAim($studyAim);
                                            }
                                        }
                                    }

                                    $this->studyProgramRepository->store($studyProgram);
                                }
                            }
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            $this->logger->warning($e->getMessage(), $e->getTrace());

            $this->io->error($e->getMessage());
        }

        $this->io->success('Success!');

        return 0;
    }
}
