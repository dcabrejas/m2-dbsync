<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model\Step;

use Dcabrejas\DBSync\Api\DBSyncStepInterface;
use Dcabrejas\DBSync\Exception\FailedCommandException;
use Dcabrejas\DBSync\Model\CommandExecutor;
use Dcabrejas\DBSync\Model\Config\Config;
use Dcabrejas\DBSync\Model\OutputChannel;
use Dcabrejas\DBSync\Model\OutputType;
use Dcabrejas\DBSync\Model\Sync\Options;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class CheckSshConnectionStep implements DBSyncStepInterface
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var CommandExecutor
     */
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
        $outputChannel->output("Checking SSH Connection");

        $cmd = sprintf(
            "limit 5 %s echo 1",
            $this->config->getSSHDetails()->getSshCommandString()
        );

        try {
            $this->commandExecutor->executeCommand($cmd, $outputChannel, $options);
        } catch (FailedCommandException $e) {
            $outputChannel->output("SSH Connection is unavailable. Halting process", OutputType::ERROR);
            return false;
        }

        return true;
    }
}
