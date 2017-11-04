<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Connection\Driver\MysqliDriver;
use QueryMule\Query\Repository\Table\Table;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class MysqliDriverTest
 * @package test\Builder\Sql\MySql
 */
class MysqliDriverTest extends TestCase
{
    public function testBindParameters()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_result = $this->getMockBuilder('mysqli_result')
                ->setMethods(['execute','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_result->expects($this->once())
                ->method('bind_param')->withConsecutive(['bdis']);

            $mysqli_result->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(false));

            return $mysqli_result;
        }));

        $driver = new MysqliDriver($mysqli,$logger);

        $fetch = $driver->fetch(new Sql(null,[
            null,
            (float)0.5,
            (int)1,
            (string)1.5]));

        $this->assertFalse($fetch);
    }

    public function testExecutionErrorLogged()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

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

    public function testFetch()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_result = $this->getMockBuilder('mysqli_result')
                ->setMethods(['execute','get_result','fetch_assoc','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_result->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_result->expects($this->once())
                ->method('get_result')
                ->will($this->returnValue($mysqli_result));

            $mysqli_result->expects($this->once())
                ->method('fetch_assoc')
                ->will($this->returnCallback(function() {
                    return 'some_result';
                }));

            return $mysqli_result;
        }));

        $driver = new MysqliDriver($mysqli,$logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertEquals('some_result',$fetch);
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

    public function testCacheSet()
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_result = $this->getMockBuilder('mysqli_result')
                ->setMethods(['execute','get_result','fetch_assoc','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_result->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_result->expects($this->once())
                ->method('get_result')
                ->will($this->returnValue($mysqli_result));

            $mysqli_result->expects($this->once())
                ->method('fetch_assoc')
                ->will($this->returnCallback(function() {
                    return 'some_result';
                }));

            return $mysqli_result;
        }));

        $driver = new MysqliDriver($mysqli,$logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testCacheGet()
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('has')->willReturn($this->returnValue(true));
        $cache->expects($this->once())->method('get');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli,$logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testFilter()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli,$logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testSelect()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli,$logger);

        $select = $driver->select([SelectInterface::SQL_STAR], Table::make($driver)->setName('some_table'));

        $this->assertTrue($select instanceof SelectInterface);
        $this->assertEquals("SELECT * FROM some_table",$select->build([
            SelectInterface::SELECT,
            SelectInterface::COLS,
            SelectInterface::FROM
        ])->sql());
    }

    public function testStatement()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli,$logger);
        $driver->filter();
        $driver->select();

        $this->assertTrue($driver->getStatement('filter') instanceof FilterInterface);
        $this->assertTrue($driver->getStatement('select') instanceof SelectInterface);
    }
}