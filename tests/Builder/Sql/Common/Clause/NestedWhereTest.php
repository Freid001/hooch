<?php
declare(strict_types=1);

namespace test\Builder\Sql\Common\Clause;

use QueryMule\Builder\Sql\Common\Clause\NestedWhere;
use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Mysql\Filter;
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

    /**
     * @var NestedWhere
     */
    private $clause;

    public function setUp()
    {
        $this->query = new QueryClass();
        $this->logical = new Logical();
        $this->comparison = new Comparison();
        $this->clause = new NestedWhere($this->query, $this->logical, '`');
    }

    public function tearDown()
    {
        $this->query = null;
        $this->logical = null;
        $this->comparison = null;
        $this->clause = null;
    }

    public function testWhereAndNestedWhere()
    {
        $this->clause->nestedWhere(function (FilterInterface $query) {
            $query->where('col_a', $this->comparison->equalTo('some_value_a'));
            $query->where('col_b', $this->comparison->equalTo('some_value_b'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->where('col_c', $this->comparison->equalTo('some_value_c'));
            }, new Filter($this->query, $this->logical));
        }, new Filter($this->query, $this->logical));

        $query = $this->query->build([
            Sql::WHERE
        ]);

        $this->assertEquals("WHERE ( `col_a` =? AND `col_b` =? AND ( `col_c` =? ) )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c'], $query->parameters());
    }

//    public function testNestedWhereOrNestedWhere()
//    {
//        $query = $this->filter->nestedWhere(function (\QueryMule\Query\Sql\Statement\FilterInterface $query) {
//            $query->where('col_a', $this->filter->comparison()->equalTo('some_value_a'));
//            $query->nestedWhere(function (\QueryMule\Query\Sql\Statement\FilterInterface $query) {
//                $query->orWhere('col_b', $this->filter->comparison()->equalTo('some_value_b'));
//            });
//        })->build();
//
//        $this->assertEquals("WHERE ( `col_a` =? OR ( `col_b` =? ) )", $query->sql());
//        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
//    }
}
