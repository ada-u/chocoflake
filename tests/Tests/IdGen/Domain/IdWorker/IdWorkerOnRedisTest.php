<?php

namespace Adachi\Tests\IdGen\Domain\IdWorker;

use Adachi\Choco\Domain\IdValue\Element\RegionId;
use Adachi\Choco\Domain\IdValue\Element\ServerId;
use Adachi\Choco\Domain\IdValue\Element\Timestamp;
use Adachi\Choco\Domain\IdConfig\IdConfig;
use Adachi\Choco\Domain\IdWorker\Redis\IdWorkerOnRedis;
use PHPUnit\Framework\TestCase;

use Mockery;

/**
 * Class IdWorkerOnRedisTest
 */
class IdWorkerOnRedisTest extends TestCase
{

    /**
     * @var IdWorkerOnRedis|Mockery\Mock
     */
    private $idWorker;

    protected function setUp(): void
    {
        $config = new IdConfig(41, 5, 5, 12, 1414334507356);
        $credential = [
            'scheme'   => 'tcp',
            'host'     => getenv('REDIS_HOST') ?: '127.0.0.1',
            'port'     => 6379
        ];
        $this->idWorker = Mockery::mock('\Adachi\Choco\Domain\IdWorker\Redis\IdWorkerOnRedis[generateTimestamp]', [$config, new RegionId(1), new ServerId(1), $credential]);
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