<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Service;

use Dcabrejas\DBSync\Api\DBSyncServiceInterface;
use Dcabrejas\DBSync\Model\Config\Config;
use Dcabrejas\DBSync\Model\OutputChannel;
use Dcabrejas\DBSync\Model\Sync\Options;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class DBSyncService implements DBSyncServiceInterface
{
    private $config;
    private $steps;

    public function __construct(Config $config, array $steps = [])
    {
        $this->config = $config;
        $this->steps = ksort($steps);
    }

    /**
     * {@inheritdoc}
     */
    public function syncRemoteDatabase(OutputChannel $outputChannel, Options $options): DBSyncServiceInterface
    {
        foreach ($this->steps as $step) {
            $result = $step->execute($outputChannel, $options);

            if (!$result) {
                return $this;
            }
        }

        $outputChannel->output("Database has been synced successfully");

        return $this;
    }
}
