<?php

declare(strict_types=1);

namespace test\Builder\Mysql;


use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Mysql\Operator\Field;
use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Operator\FieldOperatorInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;

/**
 * Class FieldTest
 * @package test\Builder\Mysql
 */
class FieldTest extends TestCase
{
    /**
     * @var FieldOperatorInterface
     */
    private $operator;

    public function setUp()
    {
        $this->operator = new Field(new Sql(), new Accent());
    }

    public function tearDown()
    {
        $this->operator = null;
    }

    public function testEq()
    {
        $sql = $this->operator->eq(\Redstraw\Hooch\Query\Field::column('column'))->sql();

        $this->assertEquals("= column", trim($sql->queryString()));
    }

    public function testNotEq()
    {
        $sql = $this->operator->notEq(\Redstraw\Hooch\Query\Field::column('column'))->sql();

        $this->assertEquals("<> column", trim($sql->queryString()));
    }

    public function testGt()
    {
        $sql = $this->operator->gt(\Redstraw\Hooch\Query\Field::column('column'))->sql();

        $this->assertEquals("> column", trim($sql->queryString()));
    }

    public function testGtEq()
    {
        $sql = $this->operator->gtEq(\Redstraw\Hooch\Query\Field::column('column'))->sql();

        $this->assertEquals(">= column", trim($sql->queryString()));
    }

    public function testLs()
    {
        $sql = $this->operator->lt(\Redstraw\Hooch\Query\Field::column('column'))->sql();

        $this->assertEquals("< column", trim($sql->queryString()));
    }

    public function testLsEq()
    {
        $sql = $this->operator->ltEq(\Redstraw\Hooch\Query\Field::column('column'))->sql();

        $this->assertEquals("<= column", trim($sql->queryString()));
    }

    public function testAnd()
    {
        $operator = $this->createMock(OperatorInterface::class);
        $operator->expects($this->any())->method('sql')->will(
            $this->onConsecutiveCalls(
                new Sql('=?'),
                new Sql(null, [])
            )
        );

        /** @var OperatorInterface $operator */
        $sql = $this->operator->and(\Redstraw\Hooch\Query\Field::column('column'), $operator)->sql();

        $this->assertEquals("AND column =?", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testOr()
    {
        $operator = $this->createMock(OperatorInterface::class);
        $operator->expects($this->any())->method('sql')->will(
            $this->onConsecutiveCalls(
                new Sql('=?'),
                new Sql(null, [])
            )
        );

        /** @var OperatorInterface $operator */
        $sql = $this->operator->or(\Redstraw\Hooch\Query\Field::column('column'), $operator)->sql();

        $this->assertEquals("OR column =?", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }

    public function testIn()
    {
        $operator = $this->createMock(OperatorInterface::class);
        $operator->expects($this->any())->method('sql')->will(
            $this->onConsecutiveCalls(
                new Sql('=?'),
                new Sql(null, [])
            )
        );

        /** @var OperatorInterface $operator */
        $sql = $this->operator->not(\Redstraw\Hooch\Query\Field::column('column'), $operator)->sql();

        $this->assertEquals("NOT column =?", trim($sql->queryString()));
        $this->assertEquals([], $sql->parameters());
    }
}
