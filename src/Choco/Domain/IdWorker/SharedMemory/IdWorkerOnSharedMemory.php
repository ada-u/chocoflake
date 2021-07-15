<?php

namespace Adachi\Choco\Domain\IdWorker\SharedMemory;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\IdValue;
use Adachi\Choco\Domain\IdConfig\IdConfig;
use Adachi\Choco\Domain\IdWorker\AbstractIdWorker;
use Adachi\Choco\Domain\IdWorker\IdWorkerInterface;

/**
 * Class IdWorkerOnSharedMemory
 *
 * @package Adachi\Choco\Domain\IdWorker\SharedMemory
 */
class IdWorkerOnSharedMemory extends AbstractIdWorker implements IdWorkerInterface
{
    /**
     * @var int
     * @fixme refactor
     */
    private int $semaphoreId;

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
     * @param IdConfig $config
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @param int $semaphoreId
     */
    public function __construct(IdConfig $config, RegionId $regionId, ServerId $serverId, int $semaphoreId = 45454)
    {
        $this->config = $config;
        $this->regionId = $regionId;
        $this->serverId = $serverId;
        $this->semaphoreId = $semaphoreId;
    }

    /**
     * @return IdValue
     */
    public function generate(): IdValue
    {
        $timestamp = $this->generateTimestamp();

        // Acquire semaphore
        $semaphore = sem_get($this->semaphoreId);
        sem_acquire($semaphore);

        // Attach shared memory
        $memory = shm_attach(self::SHM_KEY);

        $sequence = 0;

        if (! is_null($this->lastTimestamp) && $timestamp->equals($this->lastTimestamp)) {
            // Get
            $sequence = (shm_get_var($memory, self::SHM_SEQUENCE) + 1) & $this->config->getSequenceMask();

            // Increment sequence
            shm_put_var($memory, self::SHM_SEQUENCE, $sequence);

            if ($sequence === 0) {
                usleep(1000);
                $timestamp = $this->generateTimestamp();
            }
        } else {
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
}
