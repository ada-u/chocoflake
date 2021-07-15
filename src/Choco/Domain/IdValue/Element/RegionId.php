<?php

namespace Adachi\Choco\Domain\IdValue\Element;

/**
 * Class RegionId
 *
 * @package Adachi\Choco\Domain\IdValue\Element
 */
class RegionId implements ElementInterface
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
     * @param RegionId $target
     * @return bool
     */
    public function equals(RegionId $target): bool
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
