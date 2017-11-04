<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use QueryMule\Builder\Connection\Driver\MysqliDriver;
use QueryMule\Query\Sql\Sql;

/**
 * Class MysqliDriverTest
 * @package test\Builder\Sql\MySql
 */
class MysqliDriverTest extends TestCase
{
    public function testExecutionErrorLogged()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('critical');

        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_result = $this->getMockBuilder('mysqli_result')
                ->setMethods(['execute','get_result','fetch_all','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_result->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(false));

            return $mysqli_result;
        }));

        $driver = new MysqliDriver($mysqli,$logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertFalse($fetch);
    }

    public function testFetchAll()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_result = $this->getMockBuilder('mysqli_result')
                ->setMethods(['execute','get_result','fetch_all','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_result->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_result->expects($this->once())
                ->method('get_result')
                ->will($this->returnValue($mysqli_result));

            $mysqli_result->expects($this->once())
               ->method('fetch_all')
               ->will($this->returnCallback(function() {
                   return 'some_result';
               }));

           return $mysqli_result;
        }));

        $driver = new MysqliDriver($mysqli,$logger);

        $fetchAll = $driver->fetchAll(new Sql(null));

        $this->assertEquals('some_result',$fetchAll);
    }

}