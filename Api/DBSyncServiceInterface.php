<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Api;

use Dcabrejas\DBSync\Exception\FailedCommandException;
use Dcabrejas\DBSync\Model\OutputChannel;
use Dcabrejas\DBSync\Model\Sync\Options;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
interface DBSyncServiceInterface
{
    /**
     * @param OutputChannel $outputChannel
     * @param Options $options
     * @return DBSyncServiceInterface
     * @throws FailedCommandException
     */
    public function syncRemoteDatabase(OutputChannel $outputChannel, Options $options): DBSyncServiceInterface;
}
