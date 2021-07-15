<?php

namespace Adachi\Choco\Domain\IdValue;

use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\Element\Timestamp;
use Adachi\Choco\Domain\IdValue\Element\RegionId;

/**
 * Class IdValue
 *
 * @package Adachi\Choco\Domain\IdValue
 */
class IdValue
{

    /**
     * @var Timestamp
     */
    public Timestamp $timestamp;

    /**
     * @var RegionId
     */
    public RegionId $regionId;

    /**
     * @var ServerId
     */
    public ServerId $serverId;

    /**
     * @var int
     */
    public int $sequence;

    /**
     * @var int
     */
    protected int $value;

    /**
     * @param Timestamp $timestamp
     * @param RegionId $regionId
     * @param ServerId $serverId
     * @param int $sequence
     * @param int $value
     */
    public function __construct(Timestamp $timestamp, RegionId $regionId, ServerId $serverId, $sequence, $value)
    {
        $this->timestamp = $timestamp;
        $this->regionId = $regionId;
        $this->serverId = $serverId;
        $this->sequence = $sequence;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function asString(): string
    {
        return (string) $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->asString();
    }
}
