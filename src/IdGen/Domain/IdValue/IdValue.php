<?php

namespace Adachi\IdGen\Domain\IdValue;

use Adachi\IdGen\Domain\IdValue\Element\ServerId;
use Adachi\IdGen\Domain\IdValue\Element\Timestamp;
use Adachi\IdGen\Domain\IdValue\Element\RegionId;

/**
 * Class IdValue
 *
 * @package Adachi\IdGen\Domain\IdValue
 */
class IdValue
{

    /**
     * @var \Adachi\IdGen\Domain\IdValue\Element\Timestamp
     */
    public $timestamp;

    /**
     * @var \Adachi\IdGen\Domain\IdValue\Element\RegionId
     */
    public $regionId;

    /**
     * @var \Adachi\IdGen\Domain\IdValue\Element\ServerId
     */
    public $serverId;

    /**
     * @var int
     */
    public $sequence;

    /**
     * @var int
     */
    protected $value;

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
    public function toInt()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function asString()
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