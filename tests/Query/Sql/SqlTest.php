<?php

declare(strict_types=1);

namespace test\Query\Sql;


use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class SqlTest
 * @package test\Query\Sql
 */
class SqlTest extends TestCase
{
    public function testSql()
    {
        $sql = new Sql('SELECT * FROM some_table',[],false);
        $this->assertEquals("SELECT * FROM some_table", $sql->string());
    }

    public function testAppendSql()
    {
        $sql = new Sql();
        $sql->appendSql(new Sql("SELECT * FROM some_table", [], false), false);
        $this->assertEquals("SELECT * FROM some_table", $sql->string());
    }

    public function testAppendString()
    {
        $sql = new Sql();
        $sql->appendString("string", false);
        $this->assertEquals("string", $sql->string());
    }

    public function testAppendInt()
    {
        $sql = new Sql();
        $sql->appendInt(1, false);
        $this->assertEquals("1", $sql->string());
    }

    public function testParameters()
    {
        $sql = new Sql(null, [1,2,3]);
        $this->assertEquals([1,2,3],$sql->parameters());
    }
}