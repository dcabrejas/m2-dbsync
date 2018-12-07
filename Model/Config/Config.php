<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Dcabrejas\DBSync\Exception\ConfigurationMissingException;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class Config
{
    const XML_PATH_SSH_USER = 'dbsynk/remote/ssh_user';
    const XML_PATH_SSH_HOST = 'dbsynk/remote/ssh_host';
    const XML_PATH_SSH_PORT = 'dbsynk/remote/ssh_port';
    const XML_PATH_APP_PATH = 'dbsynk/remote/app_root_path';
    const XML_PATH_CONFIG_VALUES = 'dbsynk/post_sync/configuration_values';
    const DUMP_FILENAME = 'db-sync.sql';

    private $scopeConfig;
    private $serializer;

    public function __construct(ScopeConfigInterface $scopeConfig, Json $serializer)
    {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
    }

    /**
     * @return SshDetails
     * @throws ConfigurationMissingException
     */
    public function getSSHDetails(): SshDetails
    {
        $user = (string) $this->scopeConfig->getValue(self::XML_PATH_SSH_USER);
        $host = (string) $this->scopeConfig->getValue(self::XML_PATH_SSH_HOST);
        $port = (string) $this->scopeConfig->getValue(self::XML_PATH_SSH_PORT);

        if (empty($user) || empty($host) || empty($port)) {
            throw new ConfigurationMissingException(
                __(
                    "Looks like the ssh details have not been fully configured -- User: %1 Host: %2 Port: %3",
                    [$user, $host, $port]
                )
            );
        }

        return new SshDetails($user, $host, $port);
    }

    /**
    public function getSSHUser(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_SSH_USER);
    }

    public function getSSHHost(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_SSH_HOST);
    }

    public function getSSHPort(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_SSH_PORT);
    }
     * **/
    public function getRemoteAppRootPath(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_APP_PATH);
    }

    public function getConfigValues(): array
    {
        $configValues = $this->scopeConfig->getValue(self::XML_PATH_CONFIG_VALUES);

        if (is_null($configValues)) {
            return [];
        } else {
            return $this->serializer->unserialize($configValues);
        }
    }


}
