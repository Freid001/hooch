<?php

declare(strict_types=1);

namespace test\Builder\Sql\Mysql;


use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Redstraw\Hooch\Builder\Connection\Driver\MysqliDriver;
use Redstraw\Hooch\Query\Common\Operator\Comparison;
use Redstraw\Hooch\Query\Common\Operator\Logical;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Class MysqliDriverTest
 * @package test\Builder\Sql\Mysql
 */
class MysqliDriverTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var Operator
     */
    private $operator;

    public function setUp()
    {
        $this->query = new Query(new Sql(), new Accent());
        $this->query->accent()->setSymbol('`');

        $this->operator = new Operator(
            new Comparison(
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Column(new Sql(), $this->query->accent())
            ),
            new Logical(
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Column(new Sql(), $this->query->accent())
            )
        );
    }

    public function tearDown()
    {
        $this->query = null;
        $this->operator = null;
    }

    public function testBindParameters()
    {
        $logger = $this->createMock(LoggerInterface::class);

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

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
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
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

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

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertFalse($fetch);
    }

    public function testFetch()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

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

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $fetch = $driver->fetch(new Sql(null));

        $this->assertEquals('some_result',$fetch);
    }

    public function testFetchAll()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

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

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

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

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        /** @var CacheInterface $cache */
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

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        /** @var CacheInterface $cache */
        $driver->cache($cache)->fetch(new Sql(null));
    }

    public function testFilter()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
    }

    public function testSelect()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $select = $driver->select();

        $this->assertTrue($select instanceof SelectInterface);
    }

    public function testStatement()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $mysqli = $this->getMockBuilder('mysqli')->getMock();

        /**
         * @var \mysqli $mysqli
         * @var LoggerInterface $logger
         */
        $driver = new MysqliDriver($mysqli, $this->query, $logger);

        $this->assertTrue($driver->filter() instanceof FilterInterface);
        $this->assertTrue($driver->onFilter() instanceof OnFilterInterface);
        $this->assertTrue($driver->select() instanceof SelectInterface);
    }
}