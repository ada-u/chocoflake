<?php

namespace Adachi\IdGen\Service;

use Adachi\IdGen\Foundation\IdValue\Element\RegionId;
use Adachi\IdGen\Foundation\IdValue\Element\ServerId;
use Adachi\IdGen\Foundation\IdValue\IdValueConfig;
use Adachi\IdGen\Foundation\IdWorker\IdWorker;

/**
 * Class IdGenService
 *
 * @package Adachi\IdGen\Service
 */
class IdGenerateService {

    /**
     * @var \Adachi\IdGen\Foundation\IdValue\IdValueConfig
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