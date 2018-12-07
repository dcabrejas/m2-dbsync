<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Exception;

use Throwable;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class FailedCommandException extends \Exception
{
    public $exitCode;
    public $output;
    public $command;

    public function __construct(
        string $command,
        int $exitCode,
        string $output,
        Throwable $previous = null
    ) {
        $this->command  = $command;
        $this->exitCode = $exitCode;
        $this->output   = $output;
        parent::__construct($output, 0, $previous);
    }
}
