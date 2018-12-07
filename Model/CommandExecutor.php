<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model;

use Dcabrejas\DBSync\Model\Config\Config;
use Dcabrejas\DBSync\Model\Sync\Options;
use Dcabrejas\DBSync\Exception\FailedCommandException;
use Magento\Framework\Filesystem\DirectoryList;

/**
 * todo use functions such as escapeshellcmd and escapeshellarg()
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class CommandExecutor
{
    private $sshDetails;
    private $config;
    private $directoryList;

    public function __construct(Config $config, DirectoryList $directoryList)
    {
        $this->sshDetails = $config->getSSHDetails();
        $this->config = $config;
        $this->directoryList = $directoryList;
    }

    /**
     * @throws FailedCommandException
     */
    public function executeCommand(string $cmd, OutputChannel $outputChannel, Options $options)
    {
        $exitCode = 0;

        if ($options->isDebug() || $options->isDryRun()) {
            $outputChannel->output("  Running command : $cmd", OutputType::COMMENT);
        }

        if (!$options->isDryRun()) {
            $output = system($cmd, $exitCode);
        } else {
            $output = "";
        }

        if ($exitCode !== 0) {
            throw new FailedCommandException($cmd, (int) $exitCode, (string) $output);
        }
    }

    /**
     * @throws FailedCommandException
     */
    public function executeCommandFromAppRoot(string $cmd, OutputChannel $outputChannel, Options $options)
    {
        $cmd = sprintf(
            "cd %s && %s",
            $this->directoryList->getRoot(),
            $cmd
        );

        $this->executeCommand($cmd, $outputChannel, $options);
    }

    /**
     * @throws FailedCommandException
     */
    public function executeRemoteCommand(string $cmd, OutputChannel $outputChannel, Options $options)
    {
        $cmd = sprintf(
            " %s %s",
            $this->sshDetails->getSshCommandString(),
            $cmd
        );

        $this->executeCommand($cmd, $outputChannel, $options);
    }

    /**
     * @throws FailedCommandException
     */
    public function executeRemoteCommandFromAppRoot(string $cmd, OutputChannel $outputChannel, Options $options)
    {
        $cmd = sprintf(
            "cd %s && %s %s",
            $this->config->getRemoteAppRootPath(),
            $this->sshDetails->getSshCommandString(),
            $cmd
        );

        $this->executeCommand($cmd, $outputChannel, $options);
    }
}
