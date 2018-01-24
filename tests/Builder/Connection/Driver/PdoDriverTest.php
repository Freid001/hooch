<?php
declare(strict_types = 1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Connection\Driver\PdoDriver;
use QueryMule\Builder\Exception\DriverException;
use QueryMule\Query\Repository\Table\Table;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class PdoDriverTest
 * @package test\Builder\Sql\MySql
 */
class PdoDriverTest extends TestCase
{
    public function testExecutionErrorLogged()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('prepare')->will($this->returnCallback(function () {
            $PDOStatement = $this->getMockBuilder('PDOStatement')
                ->setMethods(['execute'])
                ->disableOriginalConstructor()
                ->getMock();

            $PDOStatement->expects($this->once())->method('execute')->will($this->returnValue(false));

            return $PDOStatement;
        }));

        $driver = new PdoDriver($pdo, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertFalse($fetch);
    }

    public function testFetch()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('prepare')->will($this->returnCallback(function () {
            $PDOStatement = $this->getMockBuilder('PDOStatement')
                ->setMethods(['execute', 'fetch'])
                ->disableOriginalConstructor()
                ->getMock();

            $PDOStatement->expects($this->once())->method('execute')->will($this->returnValue(true));
            $PDOStatement->expects($this->once())->method('fetch')->will($this->returnValue('some_result'));

            return $PDOStatement;
        }));

        $driver = new PdoDriver($pdo, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertEquals('some_result', $fetch);
    }

    public function testFetchAll()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('prepare')->will($this->returnCallback(function () {
            $PDOStatement = $this->getMockBuilder('PDOStatement')
                ->setMethods(['execute', 'fetchAll'])
                ->disableOriginalConstructor()
                ->getMock();

            $PDOStatement->expects($this->once())->method('execute')->will($this->returnValue(true));
            $PDOStatement->expects($this->once())->method('fetchAll')->will($this->returnValue('some_result'));

            return $PDOStatement;
        }));

        $driver = new PdoDriver($pdo, $logger);

        $fetch = $driver->fetchAll(new Sql(null));

        $this->assertEquals('some_result', $fetch);
    }

    public function testCacheSet()
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('prepare')->will($this->returnCallback(function () {
            $PDOStatement = $this->getMockBuilder('PDOStatement')
                ->setMethods(['execute', 'fetch'])
                ->disableOriginalConstructor()
                ->getMock();

            $PDOStatement->expects($this->once())->method('execute')->will($this->returnValue(true));
            $PDOStatement->expects($this->once())->method('fetch')->will($this->returnValue('some_result'));

            return $PDOStatement;
        }));

        $driver = new PdoDriver($pdo,$logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testCacheGet()
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('has')->willReturn($this->returnValue(true));
        $cache->expects($this->once())->method('get');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();

        $driver = new PdoDriver($pdo,$logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testFilterSqlite()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_SQLITE);

        $driver = new PdoDriver($pdo,$logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testFilterMysql()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_MYSQL);

        $driver = new PdoDriver($pdo,$logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testFilterPostgres()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_PGSQL);

        $driver = new PdoDriver($pdo,$logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testFilterError()
    {
        $this->expectException(DriverException::class);

        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(null);

        $driver = new PdoDriver($pdo,$logger);
        $driver->filter();
    }

    public function testSelectSqlite()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_SQLITE);

        $driver = new PdoDriver($pdo,$logger);

        $select = $driver->select([SelectInterface::SQL_STAR], Table::make($driver)->setName('some_table'));

        $this->assertTrue($select instanceof SelectInterface);
        $this->assertEquals("SELECT * FROM some_table",$select->build([
            SelectInterface::SELECT,
            SelectInterface::COLS,
            SelectInterface::FROM
        ])->sql());
    }

    public function testSelectMysql()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_MYSQL);

        $driver = new PdoDriver($pdo,$logger);

        $select = $driver->select([SelectInterface::SQL_STAR], Table::make($driver)->setName('some_table'));

        $this->assertTrue($select instanceof SelectInterface);
        $this->assertEquals("SELECT * FROM some_table",$select->build([
            SelectInterface::SELECT,
            SelectInterface::COLS,
            SelectInterface::FROM
        ])->sql());
    }

    public function testSelectPostgres()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_PGSQL);

        $driver = new PdoDriver($pdo,$logger);

        $select = $driver->select([SelectInterface::SQL_STAR], Table::make($driver)->setName('some_table'));

        $this->assertTrue($select instanceof SelectInterface);
        $this->assertEquals("SELECT * FROM some_table",$select->build([
            SelectInterface::SELECT,
            SelectInterface::COLS,
            SelectInterface::FROM
        ])->sql());
    }

    public function testSelectError()
    {
        $this->expectException(DriverException::class);

        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();

        $driver = new PdoDriver($pdo,$logger);
        $driver->select([SelectInterface::SQL_STAR], Table::make($driver)->setName('some_table'));
    }

    public function testStatementSqlite()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_SQLITE);

        $driver = new PdoDriver($pdo,$logger);
        $driver->filter();
        $driver->select();

        $this->assertTrue($driver->getStatement('filter') instanceof FilterInterface);
        $this->assertTrue($driver->getStatement('select') instanceof SelectInterface);
    }

    public function testStatementMysql()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_MYSQL);

        $driver = new PdoDriver($pdo,$logger);
        $driver->filter();
        $driver->select();

        $this->assertTrue($driver->getStatement('filter') instanceof FilterInterface);
        $this->assertTrue($driver->getStatement('select') instanceof SelectInterface);
    }

    public function testStatementPostgres()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_PGSQL);

        $driver = new PdoDriver($pdo,$logger);
        $driver->filter();
        $driver->select();

        $this->assertTrue($driver->getStatement('filter') instanceof FilterInterface);
        $this->assertTrue($driver->getStatement('select') instanceof SelectInterface);
    }
}