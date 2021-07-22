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
    private $semaphoreId;

    /**
     * Key name of shared memory block
     * @fixme refactor
     */
    const SHM_KEY = 12345;

    /**
     * @param IdConfig $config
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @param int|null $semaphoreId
     */
    public function __construct(IdConfig $config, RegionId $regionId, ServerId $serverId, $semaphoreId = null)
    {
        $this->config = $config;
        $this->regionId = $regionId;
        $this->serverId = $serverId;
        if ($semaphoreId) {
            $this->semaphoreId = $semaphoreId;
        } else {
            $this->semaphoreId = ftok(__FILE__, chr(4));
        }
        // Calculate the size of shared memory.
    }

    /**
     * @return IdValue
     */
    public function generate()
    {
        // Acquire semaphore
        $semaphore = sem_get($this->semaphoreId);
        sem_acquire($semaphore);

        // Attach shared memory
        $memory = shm_attach(self::SHM_KEY);

        $sequence = 0;

        $timestamp = $this->generateTimestamp();
        if (shm_has_var($memory, $timestamp->getValue())) {
            // Get
            $sequence = (shm_get_var($memory, $timestamp->getValue()) + 1) & $this->config->getSequenceMask();

            if ($sequence > 0) {
                // Increment sequence
                shm_put_var($memory, $timestamp->getValue(), $sequence);
            } else {
                // Sequence overflowed, rerun
                usleep(1);
                shm_detach($memory);
                sem_release($semaphore);
                return $this->generate();
            }
        } else {
            $sequence = 0;
            // Reset sequence if timestamp is different from last one.
            shm_put_var($memory, $timestamp->getValue(), $sequence);
        }

        try {
            return new IdValue(
                $timestamp,
                $this->regionId,
                $this->serverId,
                $sequence,
                $this->calculate($timestamp, $this->regionId, $this->serverId, $sequence)
            );
        } finally {
            if ($this->lastTimestamp && !$timestamp->equals($this->lastTimestamp)) {
                // Remove the previous shared memory variable.
                if ($this->lastTimestamp && shm_has_var($memory, $this->lastTimestamp->getValue())) {
                    shm_remove_var($memory, $this->lastTimestamp->getValue());
                }
            }

            // Detach shared memory
            shm_detach($memory);

            // Update lastTimestamp
            $this->lastTimestamp = $timestamp;

            // Release semaphore
            sem_release($semaphore);
        }
    }

    /**
     * Clear all data in shared memory.
     * Call this when shared memory is not freed for some reason and you get an `shm_put_var(): not enough shared memory left` (E_WARNING level) warning
     */
    public function clear() {
        $memory = shm_attach(self::SHM_KEY);
        shm_remove($memory);
        shm_detach($memory);
    }

    function __destruct() {
        // Release the last inserted shared memory.
        $memory = shm_attach(self::SHM_KEY);
        if ($this->lastTimestamp && shm_has_var($memory, $this->lastTimestamp->getValue())) {
            shm_remove_var($memory, $this->lastTimestamp->getValue());
        }
        shm_detach($memory);
    }
}
