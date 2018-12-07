<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Api;

use Dcabrejas\DBSync\Exception\FailedCommandException;
use Dcabrejas\DBSync\Model\OutputChannel;
use Dcabrejas\DBSync\Model\Sync\Options;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
interface DBSyncStepInterface
{
    /**
     * @throws FailedCommandException
     */
    public function execute(OutputChannel $outputChannel, Options $options): bool;
}
