<?php

/**
 * Opcache Service - Service for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache-service
 * @license    BSD 3-Clause
 */
namespace CPC\Service\Opcache;

use CPC\Service\Opcache\Model\ConfigurationModel;
use CPC\Service\Opcache\Model\InformationModel;
use CPC\Service\Opcache\Model\StatusModel;
use Interop\Container\ContainerInterface;

class OpcacheService implements OpcacheServiceInterface
{

    /**
     * @var ContainerInterface
     */
    private $Container;

    /**
     * @var ConfigurationModel
     */
    private $ConfigurationModel;

    /**
     * @var InformationModel
     */
    private $InformationModel;

    /**
     * @var StatusModel
     */
    private $StatusModel;

    public function __construct(ContainerInterface $container)
    {
        $this->Container = $container;
    }

    /**
     * Get Blacklist
     * 
     * @return array
     */
    public function getBlacklist()
    {
        return $this->getConfigurationModel()->getBlacklist();
    }

    public function getCachedScripts()
    {
        return $this->getStatusModel()->getScripts();
    }

    public function getDirectives()
    {
        return $this->getConfigurationModel()->getDirectives();
    }

    public function getFunctions($helpPath = null)
    {
        $model = $this->getInformationModel();

        if (is_string($helpPath)) {
            if (!$model->setHelpPageLink($helpPath)) {
                return false;
            }
        }

        return $model->getFunctions();
    }

    public function getMemoryUsage()
    {
        return $this->getStatusModel()->getMemoryUsage();
    }

    public function getStatistics()
    {
        return $this->getStatusModel()->getStatistics();
    }

    public function getStatus()
    {
        return $this->getStatusModel()->getStatus();
    }

    public function getVersionDetails()
    {
        return $this->getConfigurationModel()->getVersion();
    }

    public function invalidateFile($file)
    {
        return opcache_invalidate($file, true);
    }

    public function resetCache()
    {
        return opcache_reset();
    }

    /**
     * Get the Configuration Model
     *
     * @return ConfigurationModel
     */
    private function getConfigurationModel()
    {
        if (is_null($this->ConfigurationModel)) {
            $this->ConfigurationModel = $this->Container->get(ConfigurationModel::class);
        }
        return $this->ConfigurationModel;
    }

    /**
     * Get the Information Model
     *
     * @return InformationModel
     */
    private function getInformationModel()
    {
        if (is_null($this->InformationModel)) {
            $this->InformationModel = $this->Container->get(InformationModel::class);
        }
        return $this->InformationModel;
    }

    /**
     * Get the Status Model
     *
     * @return StatusModel
     */
    private function getStatusModel()
    {
        if (is_null($this->StatusModel)) {
            $this->StatusModel = $this->Container->get(StatusModel::class);
        }
        return $this->StatusModel;
    }
}
