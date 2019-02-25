<?php

declare(strict_types=1);

namespace test\Builder\Sql\Mysql;

use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Sql\Mysql\Filter;
use Redstraw\Hooch\Builder\Sql\Mysql\Update;
use Redstraw\Hooch\Query\Common\Operator\Comparison;
use Redstraw\Hooch\Query\Common\Operator\Logical;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\UpdateInterface;

/**
 * Class UpdateTest
 * @package test\Builder\Sql\MySql
 */
class UpdateTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var Operator
     */
    private $operator;

    /**
     * @var UpdateInterface
     */
    private $update;

    public function setUp()
    {
        $this->query = new Query(
            new Sql(),
            new Accent()
        );
        $this->query->accent()->setSymbol('`');
        $this->operator = new Operator(
            new Comparison(
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Field(new Sql(), $this->query->accent())
            ),
            new Logical(
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Field(new Sql(), $this->query->accent())
            )
        );
        $this->update = new Update($this->query, $this->operator);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->operator = null;
        $this->update = null;
    }

    public function testUpdateSet()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        /** @var RepositoryInterface $table */
        $query = $this->update->table($table)
            ->set(["key"=>"value", "another_key"=>"another_value"])
            ->set(["yet_another_key"=>"yet_another_value"])
            ->build();

        $this->assertEquals("UPDATE `some_table_name` SET `key` =?,`another_key` =?,`yet_another_key` =?", trim($query->string()));
        $this->assertEquals(['value','another_value','yet_another_value'], $query->parameters());
    }

    public function testUpdateIncrement()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        /** @var RepositoryInterface $table */
        $query = $this->update->table($table)->increment("col_a",1)->build();

        $this->assertEquals("UPDATE `some_table_name` SET `col_a` =?", trim($query->string()));
        $this->assertEquals(['col_a+1'], $query->parameters());
    }

    public function testUpdateDecrement()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        /** @var RepositoryInterface $table */
        $query = $this->update->table($table)->decrement("col_a",1)->build();

        $this->assertEquals("UPDATE `some_table_name` SET `col_a` =?", trim($query->string()));
        $this->assertEquals(['col_a-1'], $query->parameters());
    }

    public function testUpdateJoin()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));

        /** @var RepositoryInterface $table */
        /** @var RepositoryInterface $table2 */
        $query = $this->update->table($table)->join(Sql::JOIN, $table2)->set(["key"=>"value", "another_key"=>"another_value"])->build();

        $this->assertEquals("UPDATE `some_table_name` AS `t` JOIN `another_table_name` AS `tt` SET `key` =?,`another_key` =?", trim($query->string()));
        $this->assertEquals(['value','another_value'], $query->parameters());
    }

    public function testUpdateFilter()
    {
        $filter = $this->createMock(Filter::class);
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =?', ['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        /** @var RepositoryInterface $table */
        $query = $this->update->table($table)->set(["key"=>"value"])->filter(function(){})->build();

        $this->assertEquals("UPDATE `some_table_name` SET `key` =?WHERE `col_a` =?", trim($query->string()));
        $this->assertEquals(['value','some_value'], $query->parameters());
    }
}
