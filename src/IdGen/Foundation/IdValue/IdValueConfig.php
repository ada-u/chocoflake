<?php

namespace Adachi\IdGen\Foundation\IdValue;

/**
 * Class IdValueConfig
 *
 * @package Adachi\IdGen\Foundation\IdValueConfig
 */
class IdValueConfig
{
    /**
     * @var int
     */
    public $timestampBitLength;

    /**
     * @var int
     */
    public $regionIdBitLength;

    /**
     * @var int
     */
    public $serverIdBitLength;

    /**
     * @var int
     */
    public $sequenceBitLength;

    /**
     * @var int
     */
    public $maxTimestamp;

    /**
     * @var int
     */
    public $maxRegionId;

    /**
     * @var int
     */
    public $maxServerId;

    /**
     * @var int
     */
    public $maxSequence;

    /**
     * @var int
     */
    public $timestampBitShift;

    /**
     * @var int
     */
    public $regionIdBitShift;

    /**
     * @var int
     */
    public $serverIdBitShift;

    /**
     * @var int
     */
    public $timestampMask;

    /**
     * @var int
     */
    public $regionIdMask;

    /**
     * @var int
     */
    public $serverIdMask;

    /**
     * @var int
     */
    public $sequenceMask;

    /**
     * @var int
     */
    public $epoch;

    /**
     * @param int $timestampBitLength
     * @param int $regionIdBitLength
     * @param int $serverIdBitLength
     * @param int $sequenceBitLength
     * @param int $epochOffset
     */
    public function __construct($timestampBitLength, $regionIdBitLength, $serverIdBitLength, $sequenceBitLength, $epochOffset)
    {
        $this->timestampBitLength = $timestampBitLength;
        $this->regionIdBitLength = $regionIdBitLength;
        $this->serverIdBitLength = $serverIdBitLength;
        $this->sequenceBitLength = $sequenceBitLength;
        $this->epoch = $epochOffset;

        $this->maxTimestamp = -1 ^ (-1 << $this->timestampBitLength);
        $this->maxRegionId = -1 ^ (-1 << $this->regionIdBitLength);
        $this->maxServerId = -1 ^ (-1 << $this->serverIdBitLength);
        $this->maxSequence = -1 ^ (-1 << $this->sequenceBitLength);

        $this->serverIdBitShift = $this->sequenceBitLength;
        $this->regionIdBitShift = $this->serverIdBitShift + $this->serverIdBitLength;
        $this->timestampBitShift = $this->regionIdBitShift + $this->regionIdBitLength;

        $this->timestampMask = -1 ^ (-1 << ($this->timestampBitLength + $this->timestampBitShift));
        $this->regionIdMask = -1 ^ (-1 << ($this->regionIdBitLength + $this->regionIdBitShift));
        $this->serverIdMask = -1 ^ (-1 << ($this->serverIdBitLength + $this->serverIdBitShift));
        $this->sequenceMask = -1 ^ (-1 << $this->sequenceBitLength);
    }

}