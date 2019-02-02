<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\MySql\Filter;
use QueryMule\Builder\Sql\Mysql\OnFilter;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Operator\Operator;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Class OnFilterTest
 * @package test\Builder\Sql\MySql
 */
class OnFilterTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var OnFilterInterface
     */
    private $onFilter;

    public function setUp()
    {
        $this->query = new Query(new Sql(), new Logical(), new Accent());
        $this->query->accent()->setSymbol('`');

        $this->onFilter = new OnFilter($this->query);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->onFilter = null;
    }

    public function testOn()
    {
        $query = $this->onFilter->on('col_a', Operator::comparison()->equalTo('some_value'))->build();

        $this->assertEquals("ON col_a =?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testOnAndOn()
    {
        $query = $this->onFilter->on('col_a', Operator::comparison()->equalTo('some_value'))
            ->on('col_b', Operator::comparison()->equalTo('another_value'))
            ->build();

        $this->assertEquals("ON col_a =? AND col_b =?", trim($query->string()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testOnOrOn()
    {
        $query = $this->onFilter->on('col_a', Operator::comparison()->equalTo('some_value'))
            ->orOn('col_b', Operator::comparison()->equalTo('another_value'))
            ->build();

        $this->assertEquals("ON col_a =? OR col_b =?", trim($query->string()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testOnAndWhere()
    {
        $query = $this->onFilter->on('col_a', Operator::comparison()->equalTo('some_value'))
            ->where('col_b',Operator::comparison()->equalTo('another_value'))->build();

        $this->assertEquals("ON col_a =? AND `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testOnAndOnAndNestedWhere()
    {
        $query = $this->onFilter->on('col_a', Operator::comparison()->equalTo('some_value'))
            ->on('col_b', Operator::comparison()->equalTo('another_value'))
            ->nestedWhere(function(FilterInterface $filter){
                $filter->where('tt.col_c', Operator::comparison()->equalTo('yet_another_value'));
            })->build();

        $this->assertEquals("ON col_a =? AND col_b =? AND ( `tt`.`col_c` =?  )", trim($query->string()));
        $this->assertEquals(['some_value','another_value','yet_another_value'], $query->parameters());
    }
}
