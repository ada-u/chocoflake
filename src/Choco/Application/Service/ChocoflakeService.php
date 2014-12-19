<?php

namespace Adachi\Choco\Application\Service;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdConfig\IdConfig;
use Adachi\Choco\Domain\IdWorker\SharedMemory\IdWorkerOnSharedMemory;

/**
 * Class ChocoflakeService
 *
 * @package Adachi\Choco\Service
 */
class ChocoflakeService
{

    /**
     * @var \Adachi\Choco\Domain\IdConfig\IdConfig
     */
    private $config;

    /**
     * @param IdConfig $config
     */
    public function __construct(IdConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @return IdWorkerOnSharedMemory
     */
    public function createWorkerOnSharedMemory(RegionId $regionId, ServerId $serverId)
    {
        return new IdWorkerOnSharedMemory($this->config, $regionId, $serverId);
    }
}
