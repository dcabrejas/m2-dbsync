<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model\Config;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class SshDetails
{
    private $user;
    private $host;
    private $port;

    public function __construct(string $user, string $host, string $port)
    {
        $this->user = $user;
        $this->host = $host;
        $this->port = $port;
    }

    public function getSshCommandString(): string
    {
        return "ssh -p {$this->port} {$this->user}@{$this->host}";
    }

    public function getScpCommandString(string $remoteDir): string
    {
        return "scp -P {$this->port} {$this->user}@{$this->host}:$remoteDir";
    }
}
