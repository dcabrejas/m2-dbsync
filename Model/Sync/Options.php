<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model\Sync;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class Options
{
    private $debug;
    private $dryRun;

    public function __construct(bool $debug = false, bool $dryRun = false)
    {
        $this->debug  = $debug;
        $this->dryRun = $dryRun;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function isDryRun(): bool
    {
        return $this->dryRun;
    }
}
