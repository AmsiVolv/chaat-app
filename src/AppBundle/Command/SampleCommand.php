<?php
declare(strict_types=1);

namespace App\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DefaultCommand
 * @package AppBundle\Command
 */
class SampleCommand extends Command
{

    /** @var string */
    protected static $defaultName = 'cmd:sample';

    private const DESCRIPTION = 'Sample command';

    private const NAME = 'SampleCommand';

    private SymfonyStyle $io;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        // Sample of use: php bin/console sms:black-list 2 180 --env=prod
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
        return 0;
    }
}
