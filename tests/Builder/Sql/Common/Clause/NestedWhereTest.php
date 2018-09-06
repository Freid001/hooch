<?php
declare(strict_types=1);

namespace test\Builder\Sql\Common\Clause;

use QueryMule\Builder\Sql\Common\Clause\NestedWhere;
use PHPUnit\Framework\TestCase;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\QueryClass;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

class NestedWhereTest extends TestCase
{
    /**
     * @var QueryClass
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * @var Comparison
     */
    private $comparison;

    public function setUp()
    {
        $this->query = new QueryClass();
        $this->logical = new Logical();
        $this->comparison = new Comparison();
    }

    public function tearDown()
    {
        $this->query = null;
        $this->logical = null;
        $this->comparison = null;
    }

    public function testWhereAndNestedWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->any())->method('where')->will(
            $this->onConsecutiveCalls(
                $this->query->add(Sql::WHERE, new Sql('WHERE ( `col_a` =?', ['some_value_a'])),
                $this->query->add(Sql::WHERE, new Sql('AND `col_b` =?', ['some_value_b'])),
                $this->query->add(Sql::WHERE, new Sql('AND ( `col_c` =? )', ['some_value_c']))
            )
        );

        $clause = new NestedWhere($this->query, $this->logical, $filter);

        $clause->nestedWhere(function (FilterInterface $query) {
            $query->where('col_a', $this->comparison->equalTo('some_value_a'));
            $query->where('col_b', $this->comparison->equalTo('some_value_b'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->where('col_c', $this->comparison->equalTo('some_value_c'));
            });
        });

        $query = $this->query->build([
            Sql::WHERE
        ]);

        $this->assertEquals("WHERE ( `col_a` =? AND `col_b` =? AND ( `col_c` =? ) )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c'], $query->parameters());
    }

    public function testWhereOrNestedWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->any())->method('where')->will(
            $this->onConsecutiveCalls(
                $this->query->add(Sql::WHERE, new Sql('WHERE ( `col_a` =?', ['some_value_a'])),
                $this->query->add(Sql::WHERE, new Sql('AND `col_b` =?', ['some_value_b'])),
                $this->query->add(Sql::WHERE, new Sql('OR ( `col_c` =? )', ['some_value_c']))
            )
        );

        $clause = new NestedWhere($this->query, $this->logical, $filter);

        $clause->nestedWhere(function (FilterInterface $query) {
            $query->where('col_a', $this->comparison->equalTo('some_value_a'));
            $query->where('col_b', $this->comparison->equalTo('some_value_b'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->orWhere('col_c', $this->comparison->equalTo('some_value_c'));
            });
        });

        $query = $this->query->build([
            Sql::WHERE
        ]);

        $this->assertEquals("WHERE ( `col_a` =? AND `col_b` =? OR ( `col_c` =? ) )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c'], $query->parameters());
    }
}
