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
class DumpRemoteDatabaseStep implements DBSyncStepInterface
{
    private $config;
    private $commandExecutor;
    private $remoteMageRunCommand;

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
        $outputChannel->output("Dumping the remote database");

        $dumpDBCmd = sprintf(
            "php-7.1 vendor/bin/n98-magerun2 db:dump --compression=gz --strip='@trade @stripped @search' %s",
            Config::DUMP_FILENAME
        );
        $moveDumpToHomeDirCmd = sprintf("mv %s.gz ~/.", Config::DUMP_FILENAME);

        $this->commandExecutor->executeRemoteCommandFromAppRoot($dumpDBCmd, $outputChannel, $options);
        $this->commandExecutor->executeRemoteCommandFromAppRoot($moveDumpToHomeDirCmd, $outputChannel, $options);

        return true;
    }
}
