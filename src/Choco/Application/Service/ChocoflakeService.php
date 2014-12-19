<?php

namespace Adachi\Choco\Application\Service;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\IdValueConfig;
use Adachi\Choco\Domain\IdWorker\IdWorker;
use Adachi\Choco\Domain\IdWorker\SharedMemory\IdWorkerOnSharedMemory;

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
     * @return IdWorkerOnSharedMemory
     */
    public function createWorkerOnSharedMemory(RegionId $regionId, ServerId $serverId)
    {
        return new IdWorkerOnSharedMemory($this->config, $regionId, $serverId);
    }
}
