<?php
declare(strict_types=1);

namespace test\Query\Sql;

use PHPUnit\Framework\TestCase;
use QueryMule\Query\Sql\Sql;

/**
 * Class SqlTest
 * @package test\Query\Sql
 */
class SqlTest extends TestCase
{
    public function testSql()
    {
        $sql = new Sql('SELECT * FROM some_table',[],false);
        $this->assertEquals("SELECT * FROM some_table", $sql->sql());
    }

    public function testParameters()
    {
        $sql = new Sql(null, [1,2,3]);
        $this->assertEquals([1,2,3],$sql->parameters());
    }
}