<?php

namespace Adachi\Choco\Domain\IdValue\Element;

/**
 * Interface ElementInterface
 *
 * @package Adachi\Choco\Domain\IdValue\Element
 */
interface ElementInterface
{
    /**
     * @return int
     */
    public function getValue(): int;

    /**
     * @return string
     */
    public function __toString();
}
