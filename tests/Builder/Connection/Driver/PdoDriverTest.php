<?php
declare(strict_types = 1);

namespace test\Builder\Sql\MySql;


use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Redstraw\Hooch\Builder\Connection\Driver\PdoDriver;
use Redstraw\Hooch\Query\Exception\DriverException;
use Redstraw\Hooch\Query\Repository\Table\Table;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Operator\Logical;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Class PdoDriverTest
 * @package test\Builder\Sql\MySql
 */
class PdoDriverTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    public function setUp()
    {
        $this->query = new Query(new Sql(), new Logical(), new Accent());
        $this->query->accent()->setSymbol('`');
    }

    public function tearDown()
    {
        $this->query = null;
    }

    public function testExecutionErrorLogged()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('prepare')->will($this->returnCallback(function () {
            $PDOStatement = $this->getMockBuilder('PDOStatement')
                ->setMethods(['execute'])
                ->disableOriginalConstructor()
                ->getMock();

            $PDOStatement->expects($this->once())->method('execute')->will($this->returnValue(false));

            return $PDOStatement;
        }));

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertFalse($fetch);
    }

    public function testFetch()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \pdo $pdo */
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

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertEquals('some_result', $fetch);
    }

    public function testFetchAll()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \pdo $pdo */
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

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $fetch = $driver->fetchAll(new Sql(null));

        $this->assertEquals('some_result', $fetch);
    }

    public function testCacheSet()
    {
        /** @var CacheInterface $cache */
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set');

        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \pdo $pdo */
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

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testCacheGet()
    {
        /** @var CacheInterface $cache */
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('has')->willReturn($this->returnValue(true));
        $cache->expects($this->once())->method('get');

        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testFilterMysql()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_MYSQL);

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testFilterError()
    {
        $this->expectException(DriverException::class);

        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(null);

        $driver = new PdoDriver($pdo, $this->query, $logger);
        $driver->filter();
    }

    public function testSelectMysql()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_MYSQL);

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $select = $driver->select();

        $this->assertTrue($select instanceof SelectInterface);
    }

    public function testSelectError()
    {
        $this->expectException(DriverException::class);

        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();

        $driver = new PdoDriver($pdo, $this->query, $logger);
        $driver->select([Sql::SQL_STAR], Table::make($driver)->setName('some_table'));
    }

    public function testStatementMysql()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \pdo $pdo */
        $pdo = $this->getMockBuilder('PDO')->disableOriginalConstructor()->getMock();
        $pdo->expects($this->once())->method('getAttribute')->willReturn(PdoDriver::DRIVER_MYSQL);

        $driver = new PdoDriver($pdo, $this->query, $logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
        $this->assertTrue($driver->onFilter() instanceof OnFilterInterface);
        $this->assertTrue($driver->select() instanceof SelectInterface);
    }
}