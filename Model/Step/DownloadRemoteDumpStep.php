<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model\Step;

use Dcabrejas\DBSync\Api\DBSyncStepInterface;
use Dcabrejas\DBSync\Model\CommandExecutor;
use Dcabrejas\DBSync\Model\Config\Config;
use Dcabrejas\DBSync\Model\OutputChannel;
use Dcabrejas\DBSync\Model\Sync\Options;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class DownloadRemoteDumpStep implements DBSyncStepInterface
{
    private $config;
    private $commandExecutor;

    public function __construct(Config $config, CommandExecutor $commandExecutor)
    {
        $this->config = $config;
        $this->commandExecutor = $commandExecutor;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(OutputChannel $outputChannel, Options $options): bool
    {
        $outputChannel->output("Downloading the database dump");

        $downloadCommand = sprintf(
            "%s /tmp/.",
            $this->config->getSSHDetails()->getScpCommandString(Config::DUMP_FILENAME . '.gz')
        );

        $this->commandExecutor->executeCommand($downloadCommand, $outputChannel, $options);
        return true;
    }
}
