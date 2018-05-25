<?php
declare(strict_types=1);

namespace test\Builder\Sql\Mysql;

use PHPUnit\Framework\TestCase;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Sql;

/**
 * Class SelectTest
 * @package test\Builder\Sql\Mysql\Select
 */
class ComparisionTest extends TestCase
{
    /**
     * @var Comparison
     */
    private $comparison;

    public function setUp()
    {
        $this->comparison = new Comparison();
    }

    public function tearDown()
    {
        $this->comparison = null;
    }

    public function testEqualTo()
    {
        $query = $this->comparison->equalTo('some_value')->build();

        $this->assertEquals("=?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testEqualToSql()
    {
        $query = $this->comparison->equalTo(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']))->build();

        $this->assertEquals("= ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }




}
