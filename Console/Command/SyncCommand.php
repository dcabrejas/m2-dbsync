<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Console\Command;

use Dcabrejas\DBSync\Api\DBSyncServiceInterface;
use Dcabrejas\DBSync\Model\OutputChannel;
use Dcabrejas\DBSync\Service\DBSyncServiceFactory;
use Dcabrejas\DBSync\Model\OutputType;
use Dcabrejas\DBSync\Exception\ConfigurationMissingException;
use Dcabrejas\DBSync\Exception\FailedCommandException;
use Dcabrejas\DBSync\Model\Sync\Options;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class SyncCommand extends Command
{
    const DRY_RUN = 'dry-run';
    const DEBUG   = 'debug';

    private $DBSyncService;

    public function __construct(DBSyncServiceInterface $DBSyncService)
    {
        parent::__construct();
        $this->DBSyncService = $DBSyncService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('setup:db:sync')
            ->setDescription('Database Sync Command')
            ->setDefinition([
                new InputOption(
                    self::DRY_RUN,
                    '-r',
                    InputOption::VALUE_NONE,
                    'Dry Run'
                ),
                new InputOption(
                    self::DEBUG,
                    '-d',
                    InputOption::VALUE_NONE,
                    'Debug'
                ),
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = (bool) $input->getOption(self::DRY_RUN);
        $debug  = (bool) $input->getOption(self::DEBUG);

        $options = new Options($debug, $dryRun);

        $outputHandler = function (string $message, int $type = OutputType::INFO) use ($output) {

            switch ($type) {
                case OutputType::ERROR:
                    $template = "<error>%s</error>";
                    break;
                case OutputType::COMMENT:
                    $template = "<comment>%s</comment>";
                    break;
                case OutputType::INFO:
                default:
                    $template = "<info>%s</info>";
            }

            $output->writeln(sprintf($template, $message));
        };

        $outputChannel = new OutputChannel();
        $outputChannel->setOutputHandler($outputHandler);

        try {
            $this->DBSyncService->syncRemoteDatabase($outputChannel, $options);
        } catch (ConfigurationMissingException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        } catch (FailedCommandException $e) {
            $output->writeln("<error>Command failed : {$e->command}</error>");
            $output->writeln("<error>Exit code was : {$e->exitCode}</error>");
        }
    }
}
