<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use QueryMule\Builder\Connection\Driver\MysqliDriver;
use QueryMule\Query\Sql\Sql;

/**
 * Class MysqliDriverTest
 * @package test\Builder\Sql\MySql
 */
class MysqliDriverTest extends TestCase
{

    public function testFetchAll()
    {
        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $mysqli->expects($this->any())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_result = $this->getMockBuilder('mysqli_result')
                ->setMethods(['execute','get_result','fetch_all','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_result->expects($this->any())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_result->expects($this->any())
                ->method('get_result')
                ->will($this->returnValue($mysqli_result));

            $mysqli_result->expects($this->any())
               ->method('fetch_all')
               ->will($this->returnCallback(function() {
                   return '111';
               }));

           return $mysqli_result;
        }));

        $driver = new MysqliDriver($mysqli,$this->createMock(LoggerInterface::class));

        $fetchAll = $driver->fetchAll(new Sql('SELECT * FROM some_table'));

        $this->assertEquals('111',$fetchAll);
    }

}