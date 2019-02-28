<?php

declare(strict_types=1);

namespace test\Builder\Mysql;


use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Mysql\OnFilter;
use Redstraw\Hooch\Builder\Mysql\Operator\Param;
use Redstraw\Hooch\Builder\Mysql\Operator\SubQuery;
use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Field;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Query;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Class OnFilterTest
 * @package test\Builder\Sql\Mysql
 */
class OnFilterTest extends TestCase
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
     * @var OnFilterInterface
     */
    private $onFilter;

    public function setUp()
    {
        $this->query = new Query(
            new Sql(),
            new Accent()
        );
        $this->query->accent()->setSymbol('`');
        $this->operator = new Operator(
            new Param(new Sql(), $this->query->accent()),
            new \Redstraw\Hooch\Builder\Mysql\Operator\Field(new Sql(), $this->query->accent()),
            new SubQuery(new Sql(), $this->query->accent())
        );
        $this->onFilter = new OnFilter($this->query, $this->operator);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->operator = null;
        $this->onFilter = null;
    }

    public function testOn()
    {
        $query = $this->onFilter->on(Field::column('col_a'), $this->operator->field()->eq(Field::column('col_b')))->build();

        $this->assertEquals("ON `col_a` = `col_b`", trim($query->queryString()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnWithAlias()
    {
        $query = $this->onFilter->on(Field::column('t.col_a'), $this->operator->field()->eq(Field::column('col_b')))->build();

        $this->assertEquals("ON `t`.`col_a` = `col_b`", trim($query->queryString()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnAndOn()
    {
        $query = $this->onFilter->on(Field::column('col_a'), $this->operator->field()->eq(Field::column('col_b')))
            ->on(Field::column('col_c'), $this->operator->field()->eq(Field::column('col_d')))
            ->build();

        $this->assertEquals("ON `col_a` = `col_b` AND `col_c` = `col_d`", trim($query->queryString()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnOrOn()
    {
        $query = $this->onFilter->on(Field::column('col_a'), $this->operator->field()->eq(Field::column('col_b')))
            ->orOn(Field::column('col_c'), $this->operator->field()->eq(Field::column('col_d')))
            ->build();

        $this->assertEquals("ON `col_a` = `col_b` OR `col_c` = `col_d`", trim($query->queryString()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnAndWhere()
    {
        $query = $this->onFilter->on(Field::column('col_a'), $this->operator->param()->eq('some_value'))
            ->where(Field::column('col_b'),$this->operator->param()->eq('another_value'))->build();

        $this->assertEquals("ON `col_a` =? AND `col_b` =?", trim($query->queryString()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testOnOrWhere()
    {
        $query = $this->onFilter->on(Field::column('col_a'), $this->operator->param()->eq('some_value'))
            ->orWhere(Field::column('col_b'),$this->operator->param()->eq('another_value'))->build();

        $this->assertEquals("ON `col_a` =? OR `col_b` =?", trim($query->queryString()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testOnAndNestedWhere()
    {
        $query = $this->onFilter->on(Field::column('col_a'), $this->operator->param()->eq('some_value'))
            ->nestedWhere(function(){
                /** @var FilterInterface $this */
                $this->where(Field::column('tt.col_b'), $this->operator()->param()->eq('another_value'));
            })->build();

        $this->assertEquals("ON `col_a` =? AND ( `tt`.`col_b` =? )", trim($query->queryString()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }
}
