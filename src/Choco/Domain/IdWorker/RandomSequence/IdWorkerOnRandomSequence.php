<?php

namespace Adachi\Choco\Domain\IdWorker\RandomSequence;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\IdValue;
use Adachi\Choco\Domain\IdConfig\IdConfig;
use Adachi\Choco\Domain\IdWorker\AbstractIdWorker;
use Adachi\Choco\Domain\IdWorker\IdWorkerInterface;

/**
 * Class IdWorkerOnRandomSequence
 *
 * @package Adachi\Choco\Domain\IdWorker\RandomSequence
 */
class IdWorkerOnRandomSequence extends AbstractIdWorker implements IdWorkerInterface
{
    /**
     * The sequence.
     *
     * @var int
     */
    protected $sequence = 0;

    /**
     * @param IdConfig $config
     * @param RegionId $regionId
     * @param ServerId $serverId
     */
    public function __construct(IdConfig $config, RegionId $regionId, ServerId $serverId)
    {
        $this->config = $config;
        $this->regionId = $regionId;
        $this->serverId = $serverId;
    }

    /**
     * @return IdValue
     */
    public function generate()
    {
        $timestamp = $this->generateTimestamp();

        if (!is_null($this->lastTimestamp) && $timestamp->equals($this->lastTimestamp)) {
            usleep(1);
            $timestamp = $this->generateTimestamp();
        }
        // Incrementing sequence
        $this->sequence = $this->sequence++ & $this->config->getSequenceMask();

        // Update lastTimestamp
        $this->lastTimestamp = $timestamp;

        return new IdValue($timestamp, $this->regionId, $this->serverId, $this->sequence, $this->calculate($timestamp, $this->regionId, $this->serverId, $this->sequence));
    }
}
