<?php

namespace Adachi\Choco\Domain\IdWorker\Redis;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\IdValue;
use Adachi\Choco\Domain\IdConfig\IdConfig;
use Adachi\Choco\Domain\IdWorker\AbstractIdWorker;
use Adachi\Choco\Domain\IdWorker\IdWorkerInterface;
use Predis\Client;

/**
 * Class IdWorkerOnRedis
 *
 * @package Adachi\Choco\Domain\IdWorker\Redis
 */
class IdWorkerOnRedis extends AbstractIdWorker implements IdWorkerInterface
{

    /**
     * @var array
     */
    private $credential;

    /**
     * @var Client
     */
    private $redis;

    const REDIS_SEQUENCE_KEY = 'chocolate_counter';

    /**
     * @param IdConfig $config
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @param array $credential
     */
    public function __construct(IdConfig $config,
                                RegionId $regionId,
                                ServerId $serverId,
                                array $credential = [
                                    'scheme'   => 'tcp',
                                    'host'     => '127.0.0.1',
                                    'port'     => 6379
                                ]
    ) {
        $this->config = $config;
        $this->regionId = $regionId;
        $this->serverId = $serverId;
        $this->credential = $credential;

        $this->redis = new Client($this->credential);
    }

    /**
     * @return IdValue
     */
    public function generate()
    {
        $timestamp = $this->generateTimestamp();

        $sequence = 0;

        if (! is_null($this->lastTimestamp) && $timestamp->equals($this->lastTimestamp)) {
            // Get
            $sequence = $this->redis->incr(self::REDIS_SEQUENCE_KEY) & $this->config->getSequenceMask();

            if ($sequence === 0) {
                usleep(1000);
                $timestamp = $this->generateTimestamp();
            }
        } else {
            // Reset sequence if timestamp is different from last one.
            $sequence = 0;
            $this->redis->set(self::REDIS_SEQUENCE_KEY, $sequence);
        }

        // Update lastTimestamp
        $this->lastTimestamp = $timestamp;

        return new IdValue($timestamp, $this->regionId, $this->serverId, $sequence, $this->calculate($timestamp, $this->regionId, $this->serverId, $sequence));
    }
}
