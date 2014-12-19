<?php

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\Element\Timestamp;
use Adachi\Choco\Domain\IdConfig\IdConfig;

/**
 * Class IdWorkerTest
 */
class IdWorkerOnSharedMemoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Adachi\Choco\Domain\IdWorker\SharedMemory\IdWorkerOnSharedMemory|Mockery\Mock
     */
    private $idWorker;

    protected function setUp()
    {
        $config = new IdConfig(41, 5, 5, 12, 1414334507356);
        $this->idWorker = Mockery::mock('\Adachi\Choco\Domain\IdWorker\SharedMemory\IdWorkerOnSharedMemory[generateTimestamp]', [$config, new RegionId(1), new ServerId(1)]);
        $this->idWorker->shouldReceive('generateTimestamp')
            ->andReturn(new Timestamp(1000));
    }

    /**
     * @test
     */
    public function createIdValue()
    {
        $id = $this->idWorker->generate();
        // Timestamp(1000) | RegionId(1) | ServerId(1) | Sequence(0)
        // 1111101000 00001 00001 000000000000
        $this->assertSame(sprintf('%b', $this->idWorker->write($id)), '11111010000000100001000000000000');
    }

    /**
     * @test
     */
    public function convertIdValueToIntValue()
    {
        $id = $this->idWorker->generate();
        $intValue = $this->idWorker->write($id);
        $this->assertEquals($id, $this->idWorker->read($intValue));
    }
}