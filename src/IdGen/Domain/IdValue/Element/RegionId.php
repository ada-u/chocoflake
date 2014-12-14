<?php

namespace Adachi\IdGen\Domain\IdValue\Element;

/**
 * Class RegionId
 *
 * @package Adachi\IdGen\Domain\IdValue\Element
 */
class RegionId
{
    /**
     * @var int
     */
    public $value;

    /**
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param RegionId $target
     * @return bool
     */
    public function equals(RegionId $target)
    {
        return $this->value === $target->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (String) $this->value;
    }
}
