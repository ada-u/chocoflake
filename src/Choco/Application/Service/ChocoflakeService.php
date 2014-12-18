<?php

namespace Adachi\Choco\Application\Service;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\IdValueConfig;
use Adachi\Choco\Domain\IdValue\IdWorker;

/**
 * Class ChocoflakeService
 *
 * @package Adachi\Choco\Service
 */
class ChocoflakeService
{

    /**
     * @var \Adachi\Choco\Domain\IdValue\IdValueConfig
     */
    private $config;

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
