<?php

namespace Adachi\Choco\Domain\IdWorker;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\Element\Timestamp;
use Adachi\Choco\Domain\IdValue\IdValue;

abstract class AbstractIdWorker implements IdWorkerInterface
{
    /**
     * @var \Adachi\Choco\Domain\IdValue\IdValueConfig
     */
    protected $config;

    /**
     * @var \Adachi\Choco\Domain\IdValue\Element\RegionId
     */
    protected $regionId;

    /**
     * @var \Adachi\Choco\Domain\IdValue\Element\ServerId
     */
    protected $serverId;

    /**
     * (mutable)
     *
     * @var \Adachi\Choco\Domain\IdValue\Element\Timestamp
     */
    protected $lastTimestamp = null;

    /**
     * @param IdValue $value
     * @return int
     * @throws \RuntimeException
     */
    public function write(IdValue $value)
    {
        if ($value->timestamp->getValue() <= $this->config->getMaxTimestamp() &&
            $value->regionId->getValue() <= $this->config->getMaxRegionId() &&
            $value->serverId->getValue() <= $this->config->getMaxServerId() &&
            $value->sequence <= $this->config->getMaxSequence()) {
            return  $this->calculate($value->timestamp, $value->regionId, $value->serverId, $value->sequence);
        } else {
            throw new \RuntimeException("IdValue Specification is not satisfied");
        }
    }

    /**
     * @param int $value
     * @return IdValue
     * @throws \RuntimeException
     */
    public function read($value)
    {
        $timestamp = new Timestamp(($value & $this->config->getTimestampMask()) >> $this->config->getTimestampBitShift());
        $regionId = new RegionId(($value & $this->config->getRegionIdMask()) >> $this->config->getRegionIdBitShift());
        $serverId = new ServerId(($value & $this->config->getServerIdMask()) >> $this->config->getServerIdBitShift());
        $sequence = ($value & $this->config->getSequenceMask());

        if ($timestamp->getValue() <= $this->config->getMaxTimestamp() &&
            $regionId->getValue() <= $this->config->getMaxRegionId() &&
            $serverId->getValue() <= $this->config->getMaxServerId() &&
            $sequence <= $this->config->getMaxSequence()) {
            return new IdValue($timestamp, $regionId, $serverId, $sequence, $this->calculate($timestamp, $regionId, $serverId, $sequence));
        } else {
            throw new \RuntimeException("IdValue Specification is not satisfied");
        }
    }

    /**
     * @param Timestamp $timestamp
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @param $sequence
     * @return int
     */
    protected function calculate(Timestamp $timestamp, RegionId $regionId, ServerId $serverId, $sequence)
    {
        return ($timestamp->getValue() << $this->config->getTimestampBitShift()) |
        ($regionId->getValue() << $this->config->getRegionIdBitShift())   |
        ($serverId->getValue() << $this->config->getServerIdBitShift())   |
        ($sequence);
    }

    /**
     * @todo make this protected, but it'll be difficult to test. any ideas?
     * @return Timestamp
     */
    public function generateTimestamp()
    {
        $stamp = (int) round(microtime(true) * 1000);
        return new Timestamp($stamp - $this->config->getEpoch());
    }
}
