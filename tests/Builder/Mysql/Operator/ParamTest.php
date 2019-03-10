<?php

declare(strict_types=1);

namespace test\Builder\Mysql;


use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Mysql\Operator\Param;
use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Operator\ParamOperatorInterface;
use Redstraw\Hooch\Query\Sql;

/**
 * Class ParamTest
 * @package test\Builder\Mysql
 */
class ParamTest extends TestCase
{
    /**
     * @var ParamOperatorInterface
     */
    private $operator;

    public function setUp()
    {
        $this->operator = new Param(new Sql(), new Accent());
    }

    public function tearDown()
    {
        $this->operator = null;
    }

    public function testEq()
    {
        $sql = $this->operator->eq('val')->sql();

        $this->assertEquals("=?", trim($sql->queryString()));
        $this->assertEquals(["val"], $sql->parameters());
    }

    public function testNotEq()
    {
        $sql = $this->operator->notEq('val')->sql();

        $this->assertEquals("<>?", trim($sql->queryString()));
        $this->assertEquals(['val'], $sql->parameters());
    }

    public function testGt()
    {
        $sql = $this->operator->gt(1)->sql();

        $this->assertEquals(">?", trim($sql->queryString()));
        $this->assertEquals([1], $sql->parameters());
    }

    public function testGtEq()
    {
        $sql = $this->operator->gtEq(1)->sql();

        $this->assertEquals(">=?", trim($sql->queryString()));
        $this->assertEquals([1], $sql->parameters());
    }

    public function testLs()
    {
        $sql = $this->operator->lt(1)->sql();

        $this->assertEquals("<?", trim($sql->queryString()));
        $this->assertEquals([1], $sql->parameters());
    }

    public function testLsEq()
    {
        $sql = $this->operator->ltEq(1)->sql();

        $this->assertEquals("<=?", trim($sql->queryString()));
        $this->assertEquals([1], $sql->parameters());
    }

    public function testLike()
    {
        $sql = $this->operator->like('val')->sql();

        $this->assertEquals("LIKE ?", trim($sql->queryString()));
        $this->assertEquals(['val'], $sql->parameters());
    }

    public function testBetween()
    {
        $sql = $this->operator->between(1,2)->sql();

        $this->assertEquals("BETWEEN ? AND ?", trim($sql->queryString()));
        $this->assertEquals([1,2], $sql->parameters());
    }

    public function testIn()
    {
        $sql = $this->operator->in([1,2])->sql();

        $this->assertEquals("IN ( ?,? )", trim($sql->queryString()));
        $this->assertEquals([1,2], $sql->parameters());
    }
}
