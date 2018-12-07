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
class ImportDatabaseStep implements DBSyncStepInterface
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
        $outputChannel->output("Decompressing database");

        $gunzipCmd = "gunzip /tmp/" . Config::DUMP_FILENAME . ".gz";
        $this->commandExecutor->executeCommand($gunzipCmd, $outputChannel, $options);

        $outputChannel->output("Removing database definers");

        $removeDBDefinersCmd = "sed -i \"s/DEFINER=[^*]*\*/\*/g\" /tmp/" . Config::DUMP_FILENAME;
        $this->commandExecutor->executeCommand($removeDBDefinersCmd, $outputChannel, $options);

        $outputChannel->output("Importing database");

        $importDBCmd = "php-7.1 vendor/bin/n98-magerun2 db:import /tmp/" . Config::DUMP_FILENAME;
        $this->commandExecutor->executeCommandFromAppRoot($importDBCmd, $outputChannel, $options);

        return true;
    }
}
