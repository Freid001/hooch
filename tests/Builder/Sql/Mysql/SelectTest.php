<?php
declare(strict_types=1);

namespace test\Builder\Sql\Mysql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Mysql\Select;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class SelectTest
 * @package test\Builder\Sql\Mysql\Select
 */
class SelectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SelectInterface
     */
    private $select;

    /**
     * @var TableInterface
     */
    private $table;

    public function setUp()
    {
        $this->select = new Select();

        $table = $this->getMock(TableInterface::class);

        $table->expects($this->any())->method('getTableName')->will($this->returnValue('some_table_name'));

        $this->table = $table;
    }

    public function tearDown()
    {
        $this->select = null;
        $this->table = null;
    }

    public function testSelect()
    {
        $this->assertEquals("SELECT",trim($this->select->build()->sql()));
    }

    public function testIgnoreAlias()
    {
        $this->assertEquals("SELECT col_a ,col_b ,col_c",$this->select->ignoreAccent()->cols(['col_a','col_b','col_c'])->build()->sql());
    }

    public function testSelectCols()
    {
        $this->select->cols(['col_a','col_b','col_c']);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c`",trim($this->select->build()->sql()));
    }

    public function testSelectColsWithAlias()
    {
        $this->select->cols(['col_a','col_b','col_c'],'t');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c`",trim($this->select->build()->sql()));
    }

    public function testSelectAllCols()
    {
        $this->select->cols([SelectInterface::SQL_STAR]);
        $this->assertEquals("SELECT *",trim($this->select->build()->sql()));
    }

    public function testSelectColsAs()
    {
        $this->select->cols(['a'=>'col_a','b'=>'col_b','c'=>'col_c']);
        $this->assertEquals("SELECT `col_a` AS a ,`col_b` AS b ,`col_c` AS c",trim($this->select->build()->sql()));
    }

    public function testSelectColsFrom()
    {
        $this->select->cols(['col_a','col_b','col_c'])->from($this->table);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name",trim($this->select->build()->sql()));
    }

    public function testSelectColsFromWithAlias()
    {
        $this->select->cols(['col_a','col_b','col_c'],'t')->from($this->table,'t');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t",trim($this->select->build()->sql()));
    }

    public function testSelectWhere()
    {
        $query = $this->select->cols(['col_a','col_b','col_c'])->from($this->table)->where('col_a','=?','some_value')->build();
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name WHERE `col_a` =?", $query->sql());
        $this->assertEquals(['some_value'],$query->parameters());
    }

    public function testSelectWhereWithAlias()
    {
        $query = $this->select->cols(['col_a','col_b','col_c'],'t')->from($this->table)->where('t.col_a','=?','some_value')->build();
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name WHERE `t`.`col_a` =?", $query->sql());
        $this->assertEquals(['some_value'],$query->parameters());
    }

    public function testSelectWhereAndNestedWhere()
    {
        $query = $this->select->cols(['col_a','col_b','col_c'])->from($this->table)->where('col_a','=?','some_value_a')->where(function(\QueryMule\Query\Sql\Statement\SelectInterface $query){
            $query->where('col_b','=?','some_value_b');
        })->build();

        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name WHERE `col_a` =? AND ( `col_b` =? )", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }

    public function testSelectWhereAndWhere()
    {
        $query = $this->select->cols(['col_a','col_b','col_c'])->from($this->table)->where('col_a','=?','some_value_a')->where('col_b','=?','some_value_b')->build();
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name WHERE `col_a` =? AND `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }

    public function testSelectWhereOrWhere()
    {
        $query = $this->select->cols(['col_a','col_b','col_c'])->from($this->table)->where('col_a','=?','some_value_a')->orWhere('col_b','=?','some_value_b')->build();
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name WHERE `col_a` =? OR `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }
}