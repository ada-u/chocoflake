<?php

namespace Adachi\Choco\Domain\IdValue;

use RuntimeException;

/**
 * Class IdValueConfig
 *
 * @package Adachi\Choco\Domain\IdValueConfig
 */
class IdValueConfig
{
    /**
     * @var int
     */
    private $timestampBitLength;

    /**
     * @var int
     */
    private $regionIdBitLength;

    /**
     * @var int
     */
    private $serverIdBitLength;

    /**
     * @var int
     */
    private $sequenceBitLength;

    /**
     * @var int
     */
    private $maxTimestamp;

    /**
     * @var int
     */
    private $maxRegionId;

    /**
     * @var int
     */
    private $maxServerId;

    /**
     * @var int
     */
    private $maxSequence;

    /**
     * @var int
     */
    private $timestampBitShift;

    /**
     * @var int
     */
    private $regionIdBitShift;

    /**
     * @var int
     */
    private $serverIdBitShift;

    /**
     * @var int
     */
    private $timestampMask;

    /**
     * @var int
     */
    private $regionIdMask;

    /**
     * @var int
     */
    private $serverIdMask;

    /**
     * @var int
     */
    private $sequenceMask;

    /**
     * @var int
     */
    private $epoch;

    /**
     * @param int $timestampBitLength
     * @param int $regionIdBitLength
     * @param int $serverIdBitLength
     * @param int $sequenceBitLength
     * @param int $epochOffset
     */
    public function __construct($timestampBitLength, $regionIdBitLength, $serverIdBitLength, $sequenceBitLength, $epochOffset)
    {
        if ($timestampBitLength + $regionIdBitLength + $serverIdBitLength + $sequenceBitLength > 63) {
            throw new RuntimeException("IdValue bit length must be less than 64 bit.");
        }
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

    /**
     * @return int
     */
    public function getTimestampBitLength()
    {
        return $this->timestampBitLength;
    }

    /**
     * @return int
     */
    public function getRegionIdBitLength()
    {
        return $this->regionIdBitLength;
    }

    /**
     * @return int
     */
    public function getServerIdBitLength()
    {
        return $this->serverIdBitLength;
    }

    /**
     * @return int
     */
    public function getSequenceBitLength()
    {
        return $this->sequenceBitLength;
    }

    /**
     * @return int
     */
    public function getMaxTimestamp()
    {
        return $this->maxTimestamp;
    }

    /**
     * @return int
     */
    public function getMaxRegionId()
    {
        return $this->maxRegionId;
    }

    /**
     * @return int
     */
    public function getMaxServerId()
    {
        return $this->maxServerId;
    }

    /**
     * @return int
     */
    public function getMaxSequence()
    {
        return $this->maxSequence;
    }

    /**
     * @return int
     */
    public function getTimestampBitShift()
    {
        return $this->timestampBitShift;
    }

    /**
     * @return int
     */
    public function getRegionIdBitShift()
    {
        return $this->regionIdBitShift;
    }

    /**
     * @return int
     */
    public function getServerIdBitShift()
    {
        return $this->serverIdBitShift;
    }

    /**
     * @return int
     */
    public function getTimestampMask()
    {
        return $this->timestampMask;
    }

    /**
     * @return int
     */
    public function getRegionIdMask()
    {
        return $this->regionIdMask;
    }

    /**
     * @return int
     */
    public function getServerIdMask()
    {
        return $this->serverIdMask;
    }

    /**
     * @return int
     */
    public function getSequenceMask()
    {
        return $this->sequenceMask;
    }

    /**
     * @return int
     */
    public function getEpoch()
    {
        return $this->epoch;
    }
}
