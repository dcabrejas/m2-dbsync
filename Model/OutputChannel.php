<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model;

use Dcabrejas\DBSync\Model\OutputType;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class OutputChannel
{
    private $outputHandler;

    public function setOutputHandler(\Closure $handler)
    {
        $this->outputHandler = $handler;
    }

    public function output(string $message, int $type = OutputType::INFO)
    {
        if ($this->outputHandler !== null) {
            $this->outputHandler->__invoke($message, $type);
        }
    }
}
