<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
abstract class OutputType
{
    const INFO    = 0;
    const COMMENT = 1;
    const ERROR   = 2;
}
