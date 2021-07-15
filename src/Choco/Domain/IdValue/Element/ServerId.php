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
    private int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param ServerId $target
     * @return bool
     */
    public function equals(ServerId $target): bool
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
