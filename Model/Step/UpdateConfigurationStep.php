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
class UpdateConfigurationStep implements DBSyncStepInterface
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
        /**
        protected function updateConfigurationValues()
        {
        //$this->output("Updating configuration values");

        //todo scope
        $configValues = $this->config->getConfigValues();

        foreach ($configValues as $configValue) {
        $this->commandExecutor->executeCommand(
        sprintf(
        "%s config:set '%s' '%s'",
        $this->localMageRunCommand,
        $configValue['path'],
        $configValue['value']
        )
        );
        }
        }
         **/

        return true;
    }
}
