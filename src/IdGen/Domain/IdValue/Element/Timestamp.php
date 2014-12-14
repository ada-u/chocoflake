<?php

namespace Adachi\IdGen\Domain\IdValue\Element;

/**
 * Class Timestamp
 *
 * @package Adachi\IdGen\Domain\IdValue\Element
 */
class Timestamp
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
     * @param Timestamp $target
     * @return bool
     */
    public function equals(Timestamp $target)
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