<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Sql\MySql\Filter;
use Redstraw\Hooch\Builder\Sql\Mysql\OnFilter;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Operator\Comparison;
use Redstraw\Hooch\Query\Sql\Operator\Logical;
use Redstraw\Hooch\Query\Sql\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

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
