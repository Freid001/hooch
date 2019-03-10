<?php

declare(strict_types=1);

namespace test\Builder\Mysql;


use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Mysql\Operator\Param;
use Redstraw\Hooch\Builder\Mysql\Operator\SubQuery;
use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Operator\ParamOperatorInterface;
use Redstraw\Hooch\Query\Sql;

/**
 * Class SubQueryTest
 * @package test\Builder\Mysql
 */
class SubQueryTest extends TestCase
{
    /**
     * @var SubQuery
     */
    private $operator;

    public function setUp()
    {
        $this->operator = new SubQuery(new Sql(), new Accent());
    }

    public function tearDown()
    {
        $this->operator = null;
    }

    public function testEq()
    {
        $sql = $this->operator->eq(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("= ( SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testNotEq()
    {
        $sql = $this->operator->notEq(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("<> ( SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testGt()
    {
        $sql = $this->operator->gt(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("> ( SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testGtEq()
    {
        $sql = $this->operator->gtEq(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals(">= ( SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testLs()
    {
        $sql = $this->operator->lt(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("< ( SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testLsEq()
    {
        $sql = $this->operator->ltEq(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("<= ( SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testAll()
    {
        $sql = $this->operator->all(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("ALL (SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testAny()
    {
        $sql = $this->operator->any(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("ANY (SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testSome()
    {
        $sql = $this->operator->some(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("SOME (SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testExists()
    {
        $sql = $this->operator->exists(new Sql('SELECT * FROM `some_table`'))->sql();

        $this->assertEquals("EXISTS (SELECT * FROM `some_table` )", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }
}
