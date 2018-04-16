<?php
declare(strict_types=1);

namespace test\Query\Repository\Table;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Sqlite\Filter;
use QueryMule\Builder\Sql\Sqlite\Select;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Repository\Table\Table;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Sql\Operator\Comparison;

/**
 * Class TableTest
 * @package test\Query\Repository\Table
 */
class TableTest extends TestCase
{
    public function testMake()
    {
        $driver = $this->createMock(DriverInterface::class);

        $this->assertTrue(Table::make($driver) instanceof RepositoryInterface);
    }

    public function testSetName()
    {
        $driver = $this->createMock(DriverInterface::class);

        $table = new Table($driver);
        $table->setName('some_table_name');

        $this->assertEquals("some_table_name", $table->getName());
    }

    public function testFilter()
    {
        $driver = $this->createMock(DriverInterface::class);
        $driver->expects($this->once())->method('filter')->willReturn(new Filter());

        $table = new Table($driver);

        $query = $table->filter()->where('a',Comparison::equalTo(),'b')->build([
            SelectInterface::WHERE
        ]);

        $this->assertEquals("WHERE `a` =?", $query->sql());
        $this->assertEquals(['b'], $query->parameters());
    }

    public function testSelect()
    {
        $driver = $this->createMock(DriverInterface::class);
        $driver->expects($this->once())->method('select')->willReturn(new Select());

        $table = new Table($driver);
        $table->setName('some_table_name');

        $this->assertEquals("SELECT * FROM some_table_name", $table->select()->build([
            SelectInterface::SELECT,
            SelectInterface::COLS,
            SelectInterface::FROM
        ])->sql());
    }
}