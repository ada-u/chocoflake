<?php

namespace Adachi\Choco\Domain\IdWorker;

use Adachi\Choco\Domain\IdValue\IdValue;

/**
 * Interface IdWorkerInterface
 *
 * @package Adachi\Choco\Domain\IdWorker
 */
interface IdWorkerInterface
{
    /**
     * @param IdValue $value
     * @return int
     * @throws \RuntimeException
     */
    public function write(IdValue $value): int;

    /**
     * @param int $value
     * @return IdValue
     * @throws \RuntimeException
     */
    public function read($value): IdValue;

    /**
     * @return IdValue
     */
    public function generate(): IdValue;
}
