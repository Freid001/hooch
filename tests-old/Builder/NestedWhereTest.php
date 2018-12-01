<?php
declare(strict_types=1);

namespace test\Builder\Sql\Common\Clause;

use QueryMule\Builder\Sql\Common\Clause\NestedWhere;
use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

class NestedWhereTest extends TestCase
{
    /**
     * @var Query
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
        $this->query = new Query();
        $this->logical = new Logical();
        $this->comparison = new Comparison();
    }

    public function tearDown()
    {
        $this->query = null;
        $this->logical = null;
        $this->comparison = null;
    }

    public function testNestedWhere()
    {
        $clause = new NestedWhere($this->query, new Filter($this->query, $this->logical->setNested(true)));

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
}
