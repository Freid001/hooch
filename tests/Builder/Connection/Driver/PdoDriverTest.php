<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use QueryMule\Builder\Connection\Driver\MysqliDriver;
use QueryMule\Builder\Connection\Driver\PdoDriver;
use QueryMule\Query\Connection\DatabaseInterface;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Class PdoDriverTest
 * @package test\Builder\Sql\MySql
 */
class PdoDriverTest extends TestCase
{
    public function testExecutionErrorLogged()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('critical');

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->will($this->returnValue(DriverInterface::DRIVER_MYSQL));

        
        $pdo->expects($this->once())->method('execute')->will($this->returnValue(false));

        $driver = new PdoDriver($pdo,$logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertFalse($fetch);
    }






}