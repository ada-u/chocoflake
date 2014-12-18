<?php

namespace Adachi\Choco\Domain\IdValue\Element;

/**
 * Class ServerId
 *
 * @package Adachi\Choco\Domain\IdValue\Element
 */
class ServerId implements ElementInterface
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
     * @param ServerId $target
     * @return bool
     */
    public function equals(ServerId $target)
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
