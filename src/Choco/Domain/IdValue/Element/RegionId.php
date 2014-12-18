<?php

namespace Adachi\Choco\Domain\IdValue\Element;

/**
 * Class RegionId
 *
 * @package Adachi\Choco\Domain\IdValue\Element
 */
class RegionId
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
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
