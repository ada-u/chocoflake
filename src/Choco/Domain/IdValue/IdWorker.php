<?php

namespace Adachi\Choco\Domain\IdValue;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\Element\Timestamp;

/**
 * Class IdWorker
 *
 * @package Adachi\Choco\Domain\IdWorker
 */
class IdWorker
{
    /**
     * @var \Adachi\Choco\Domain\IdValue\IdValueConfig
     */
    private $config;

    /**
     * @var \Adachi\Choco\Domain\IdValue\Element\RegionId
     */
    private $regionId;

    /**
     * @var \Adachi\Choco\Domain\IdValue\Element\ServerId
     */
    private $serverId;

    /**
     * (mutable)
     *
     * @var \Adachi\Choco\Domain\IdValue\Element\Timestamp
     */
    private $lastTimestamp = null;

    /**
     * @var int
     * @fixme refactor
     */
    private $semaphoreId;

    /**
     * Key name of shared memory block
     * @fixme refactor
     */
    const SHM_KEY = 12345;

    /**
     * Key name of shared memory segment
     * @fixme refactor
     */
    const SHM_SEQUENCE = 54321;

    /**
     * @param IdValueConfig $config
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @param int $semaphoreId
     */
    public function __construct(IdValueConfig $config, RegionId $regionId, ServerId $serverId, $semaphoreId = 45454)
    {
        $this->config = $config;
        $this->regionId = $regionId;
        $this->serverId = $serverId;
        $this->semaphoreId = $semaphoreId;
    }


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
            $value->sequence <= $this->config->getMaxSequence())
        {
            return  $this->calculate($value->timestamp, $value->regionId, $value->serverId, $value->sequence);
        }
        else
        {
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
            $sequence <= $this->config->getMaxSequence())
        {
            return new IdValue($timestamp, $regionId, $serverId, $sequence, $this->calculate($timestamp, $regionId, $serverId, $sequence));
        }
        else
        {
            throw new \RuntimeException("IdValue Specification is not satisfied");
        }
    }

    /**
     * @return IdValue
     */
    public function generate()
    {
        $timestamp = $this->generateTimestamp();

        // Acquire semaphore
        $semaphore = sem_get($this->semaphoreId);
        sem_acquire($semaphore);

        // Attach shared memory
        $memory = shm_attach(self::SHM_KEY);

        $sequence = 0;

        if ( ! is_null($this->lastTimestamp) && $timestamp->equals($this->lastTimestamp))
        {
            // Get
            $sequence = (shm_get_var($memory, self::SHM_SEQUENCE) + 1) & $this->config->getSequenceMask();

            // Increment sequence
            shm_put_var($memory, self::SHM_SEQUENCE, $sequence);

            if ($sequence === 0)
            {
                usleep(1000);
                $timestamp = $this->generateTimestamp();
            }
        }
        else
        {
            // Reset sequence if timestamp is different from last one.
            $sequence = 0;
            shm_put_var($memory, self::SHM_SEQUENCE, $sequence);
        }

        // Detach shared memory
        shm_detach($memory);

        // Release semaphore
        sem_release($semaphore);

        // Update lastTimestamp
        $this->lastTimestamp = $timestamp;

        return new IdValue($timestamp, $this->regionId, $this->serverId, $sequence, $this->calculate($timestamp, $this->regionId, $this->serverId, $sequence));
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
}