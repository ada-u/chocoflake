<?php

namespace Adachi\Choco\Domain\IdValue\Element;

/**
 * Class Timestamp
 *
 * @package Adachi\Choco\Domain\IdValue\Element
 */
class Timestamp implements ElementInterface
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
