<?php
declare(strict_types=1);

namespace App\Command\Special;

use App\Entity\GroupConversation;
use App\Repository\CourseRepository;
use App\Repository\GroupConversationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

/**
 * Class MakeGroupConversationsCommand
 * @package App\Command\Special
 */
class MakeGroupConversationsCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'vse:conversations:groups';
    private const DESCRIPTION = 'Make group conversations from courses';
    private const SUCCESS_MESSAGE = 'Were created [%d] conversations';
    private const DEFAULT_MAX_CAPACITY = 30;

    private GroupConversationRepository $conversationRepository;
    private CourseRepository $courseRepository;
    private LoggerInterface $logger;
    private SymfonyStyle $io;

    public function __construct(
        GroupConversationRepository $conversationRepository,
        CourseRepository $courseRepository,
        LoggerInterface $logger,
    ) {
        parent::__construct();
        $this->conversationRepository = $conversationRepository;
        $this->courseRepository = $courseRepository;
        $this->logger = $logger;
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
        $createdConversations = 0;

        $courses = $this->courseRepository->findAll();
        foreach ($courses as $course) {
            $groupConversation = new GroupConversation(
                sprintf('#%s', $course->getSubjectCode()),
                sprintf('#%s', $this->getRandomColor()),
                self::DEFAULT_MAX_CAPACITY,
                $course
            );

            try {
                $this->conversationRepository->store($groupConversation);
                $createdConversations++;
            } catch (Throwable $e) {
                $this->logger->critical($e->getMessage(), $e->getTrace());
                $this->io->error($e->getMessage());
            }
        }

        $this->io->success(
            sprintf(self::SUCCESS_MESSAGE, $createdConversations)
        );

        return 0;
    }

    private function getRandomColorPart(): string
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    private function getRandomColor(): string
    {
        return sprintf('%s%s%s', $this->getRandomColorPart(), $this->getRandomColorPart(), $this->getRandomColorPart());
    }
}
