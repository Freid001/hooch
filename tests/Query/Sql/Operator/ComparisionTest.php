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

        $this->assertEquals("=?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testEqualToSql()
    {
        $query = $this->comparison->equalTo(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']))->build();

        $this->assertEquals("= ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testGreaterThan()
{
    $query = $this->comparison->greaterThan('some_value')->build();

    $this->assertEquals(">?", trim($query->string()));
    $this->assertEquals(['some_value'], $query->parameters());
}

    public function testGreaterThanSql()
    {
        $query = $this->comparison->greaterThan(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']), true)->build();

        $this->assertEquals("> ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testGreaterThanEqualTo()
    {
        $query = $this->comparison->greaterThanEqualTo('some_value')->build();

        $this->assertEquals(">=?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testGreaterThanEqualToSql()
    {
        $query = $this->comparison->greaterThanEqualTo(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']))->build();

        $this->assertEquals(">= ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testLessThan()
    {
        $query = $this->comparison->lessThan('some_value')->build();

        $this->assertEquals("<?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testLessThanSql()
    {
        $query = $this->comparison->lessThan(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']))->build();

        $this->assertEquals("< ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testLessThanEqualTo()
    {
        $query = $this->comparison->lessThanEqualTo('some_value')->build();

        $this->assertEquals("<=?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testLessThanEqualToSql()
    {
        $query = $this->comparison->lessThanEqualTo(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']))->build();

        $this->assertEquals("<= ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testNotEqualTo()
    {
        $query = $this->comparison->notEqualTo('some_value')->build();

        $this->assertEquals("<>?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testNotEqualToSql()
    {
        $query = $this->comparison->notEqualTo(new Sql('SELECT * FROM some_table WHERE col_a = some_value',['some_value']))->build();

        $this->assertEquals("<> ( SELECT * FROM some_table WHERE col_a = some_value )", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }
}