<?php
declare(strict_types=1);

namespace test\Builder\Sql\Sqlite;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Sqlite\Filter;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Sql\Operator\Comparison;

/**
 * Class FilterTest
 * @package test\Builder\Sql\Sqlite
 */
class FilterTest extends TestCase
{
    /**
     * @var FilterInterface
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new Filter();
    }

    public function tearDown()
    {
        $this->filter = null;
    }

    public function testSelectWhere()
    {
        $query = $this->filter->where('col_a',Comparison::equalTo(),'some_value')->build();
        $this->assertEquals("WHERE `col_a` =?", $query->sql());
        $this->assertEquals(['some_value'],$query->parameters());
    }

    public function testSelectWhereWithAlias()
    {
        $query = $this->filter->where('t.col_a',Comparison::equalTo(),'some_value')->build();
        $this->assertEquals("WHERE `t`.`col_a` =?", $query->sql());
        $this->assertEquals(['some_value'],$query->parameters());
    }

    public function testSelectWhereAndNestedWhere()
    {
        $query = $this->filter->where('col_a',Comparison::equalTo(),'some_value_a')->where(function(\QueryMule\Query\Sql\Statement\FilterInterface $query){
            $query->where('col_b',Comparison::equalTo(),'some_value_b');
        })->build();

        $this->assertEquals("WHERE `col_a` =? AND ( `col_b` =? )", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }

    public function testSelectWhereAndWhere()
    {
        $query = $this->filter->where('col_a',Comparison::equalTo(),'some_value_a')->where('col_b',Comparison::equalTo(),'some_value_b')->build();
        $this->assertEquals("WHERE `col_a` =? AND `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }

    public function testSelectWhereOrWhere()
    {
        $query = $this->filter->where('col_a',Comparison::equalTo(),'some_value_a')->orWhere('col_b',Comparison::equalTo(),'some_value_b')->build();
        $this->assertEquals("WHERE `col_a` =? OR `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }
}