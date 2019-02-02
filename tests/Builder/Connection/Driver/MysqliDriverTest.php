<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;


use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use QueryMule\Builder\Connection\Driver\MysqliDriver;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class MysqliDriverTest
 * @package test\Builder\Sql\MySql
 */
class MysqliDriverTest extends TestCase
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

    public function testBindParameters()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_stmt = $this->getMockBuilder('mysqli_stmt')
                ->setMethods(['execute','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_stmt->expects($this->once())
                ->method('bind_param')->withConsecutive(['bdis']);

            $mysqli_stmt->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(false));

            return $mysqli_stmt;
        }));

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null,[
            null,
            (float)0.5,
            (int)1,
            (string)1.5]));

        $this->assertFalse(!empty($fetch));
    }

    public function testExecutionErrorLogged()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_stmt = $this->getMockBuilder('mysqli_stmt')
                ->setMethods(['execute','get_result','fetch_all','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_stmt->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(false));

            return $mysqli_stmt;
        }));

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertFalse($fetch);
    }

    public function testFetch()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_stmt = $this->getMockBuilder('mysqli_stmt')
                ->setMethods(['execute','get_result','fetch_assoc','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_stmt->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_stmt->expects($this->once())
                ->method('get_result')
                ->will($this->returnValue($mysqli_stmt));

            $mysqli_stmt->expects($this->once())
                ->method('fetch_assoc')
                ->will($this->returnCallback(function() {
                    return 'some_result';
                }));

            return $mysqli_stmt;
        }));

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertEquals('some_result',$fetch);
    }

    public function testFetchAll()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_stmt = $this->getMockBuilder('mysqli_stmt')
                ->setMethods(['execute','get_result','fetch_all','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_stmt->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_stmt->expects($this->once())
                ->method('get_result')
                ->will($this->returnValue($mysqli_stmt));

            $mysqli_stmt->expects($this->once())
               ->method('fetch_all')
               ->will($this->returnCallback(function() {
                   return 'some_result';
               }));

           return $mysqli_stmt;
        }));

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $fetchAll = $driver->fetchAll(new Sql(null));

        $this->assertEquals('some_result',$fetchAll);
    }

    public function testCacheSet()
    {
        /** @var CacheInterface $cache */
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set');

        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();
        $mysqli->expects($this->once())->method('prepare')->will($this->returnCallback(function() {
            $mysqli_stmt = $this->getMockBuilder('mysqli_stmt')
                ->setMethods(['execute','get_result','fetch_assoc','bind_param'])
                ->disableOriginalConstructor()
                ->getMock();

            $mysqli_stmt->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

            $mysqli_stmt->expects($this->once())
                ->method('get_result')
                ->will($this->returnValue($mysqli_stmt));

            $mysqli_stmt->expects($this->once())
                ->method('fetch_assoc')
                ->will($this->returnCallback(function() {
                    return 'some_result';
                }));

            return $mysqli_stmt;
        }));

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

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

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testFilter()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testSelect()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $select = $driver->select();

        $this->assertTrue($select instanceof SelectInterface);
    }

    public function testStatement()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var \mysqli $mysqli */
        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
        $this->assertTrue($driver->onFilter() instanceof OnFilterInterface);
        $this->assertTrue($driver->select() instanceof SelectInterface);
    }
}