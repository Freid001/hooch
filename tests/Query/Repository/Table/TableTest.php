<?php
//declare(strict_types=1);
//
//namespace test\Query\Repository\Table;
//
//use PHPUnit\Framework\TestCase;
//use Redstraw\Hooch\Builder\Sql\Sqlite\Filter;
//use Redstraw\Hooch\Builder\Sql\Sqlite\Select;
//use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
//use Redstraw\Hooch\Query\Repository\RepositoryInterface;
//use Redstraw\Hooch\Query\Repository\Table\Table;
//use Redstraw\Hooch\Query\Sql\Sql;
//use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;
//use Redstraw\Hooch\Sql\Operator\Comparison;
//
///**
// * Class TableTest
// * @package test\Query\Repository\Table
// */
//class TableTest extends TestCase
//{
//    public function testMake()
//    {
//        $driver = $this->createMock(DriverInterface::class);
//
//        $this->assertTrue(Table::make($driver) instanceof RepositoryInterface);
//    }
//
//    public function testSetName()
//    {
//        $driver = $this->createMock(DriverInterface::class);
//
//        $table = new Table($driver);
//        $table->setName('some_table_name');
//
//        $this->assertEquals("some_table_name", $table->getName());
//    }
//
//    public function testFilter()
//    {
//        $driver = $this->createMock(DriverInterface::class);
//        $driver->expects($this->once())->method('filter')->willReturn(new Filter());
//
//        $table = new Table($driver);
//
//        $query = $table->filter()->where('a',$table->filter()->comparison()->equalTo('b'))->build([
//            Sql::WHERE
//        ]);
//
//        $this->assertEquals("WHERE `a` =?", $query->sql());
//        $this->assertEquals(['b'], $query->parameters());
//    }
//
//    public function testSelect()
//    {
//        $driver = $this->createMock(DriverInterface::class);
//        $driver->expects($this->once())->method('select')->willReturn(new Select());
//
//        $table = new Table($driver);
//        $table->setName('some_table_name');
//
//        $this->assertEquals("SELECT * FROM some_table_name", $table->select()->build([
//            Sql::SELECT,
//            Sql::COLS,
//            Sql::FROM
//        ])->sql());
//    }
//}