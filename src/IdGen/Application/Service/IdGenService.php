<?php

namespace Adachi\IdGen\Application\Service;

use Adachi\IdGen\Domain\IdValue\Element\RegionId;
use Adachi\IdGen\Domain\IdValue\Element\ServerId;
use Adachi\IdGen\Domain\IdValue\IdValueConfig;
use Adachi\IdGen\Domain\IdValue\IdWorker;

/**
 * Class IdGenService
 *
 * @package Adachi\IdGen\Service
 */
class IdGenerateService {

    /**
     * @var \Adachi\IdGen\Domain\IdValue\IdValueConfig
     */
    protected $config;

    /**
     * @param IdValueConfig $config
     */
    public function __construct(IdValueConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @return IdWorker
     */
    public function createIdWorker(RegionId $regionId, ServerId $serverId)
    {
        return new IdWorker($this->config, $regionId, $serverId);
    }
}