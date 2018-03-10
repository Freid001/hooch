<?php
declare(strict_types=1);

namespace test\Builder\Sql\Mysql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Mysql\Select;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class SelectTest
 * @package test\Builder\Sql\Mysql\Select
 */
class SelectTest extends TestCase
{
    /**
     * @var SelectInterface
     */
    private $select;

    public function setUp()
    {
        $this->select = new Select();
    }

    public function tearDown()
    {
        $this->select = null;
    }

    public function testSelect()
    {
        $this->assertEquals("SELECT",trim($this->select->build([
            Select::SELECT
        ])->sql()));
    }

    public function testIgnoreAlias()
    {
        $this->assertEquals("SELECT col_a ,col_b ,col_c",$this->select->ignoreAccent()->cols(['col_a','col_b','col_c'])->build([
            Select::SELECT,
            Select::COLS
        ])->sql());
    }

    public function testSelectCols()
    {
        $this->select->cols(['col_a'])->cols(['col_b'])->cols(['col_c']);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c`",trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectColsWithAlias()
    {
        $this->select->cols(['col_a','col_b','col_c'],'t');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c`",trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectAllCols()
    {
        $this->select->cols([SelectInterface::SQL_STAR]);
        $this->assertEquals("SELECT *",trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectColsAs()
    {
        $this->select->cols(['a'=>'col_a','b'=>'col_b','c'=>'col_c']);
        $this->assertEquals("SELECT `col_a` AS a ,`col_b` AS b ,`col_c` AS c",trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectColsFrom()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a','col_b','col_c'])->from($table);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM
        ])->sql()));
    }

    public function testSelectColsFromWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a','col_b','col_c'],'t')->from($table,'t');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM
        ])->sql()));
    }

    public function testSelectWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('where');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =?',['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a'])->from($table)->where('col_a','=?','some_value')->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM some_table_name WHERE `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testSelectWhereOrWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('where');
        $filter->expects($this->once())->method('orWhere');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =? OR `col_b` =?',['some_value','another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query =$this->select->cols(['col_a','col_b'])->from($table)->where('col_a','=?','some_value')->orWhere('col_b','=?','another_value')->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` =? OR `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testSelectWhereAndWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(2))->method('where');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =? AND `col_b` =?',['some_value','another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query =$this->select->cols(['col_a','col_b'])->from($table)->where('col_a','=?','some_value')->where('col_b','=?','another_value')->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` =? AND `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testSelectColsFromGroupBy()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->groupBy('col_a')->groupBy('col_b');
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name GROUP BY `col_a` ,`col_b`",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::GROUP
        ])->sql()));
    }

    public function testSelectColsFromGroupByWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->groupBy('col_a', 't')->groupBy('col_b','t');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t GROUP BY `t`.`col_a` ,`t`.`col_b`",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::GROUP
        ])->sql()));
    }

    public function testSelectColsFromOrderBy()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->orderBy('col_a')->orderBy('col_b','asc');
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name ORDER BY `col_a` DESC ,`col_b` ASC",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::ORDER
        ])->sql()));
    }

    public function testSelectColsFromOrderByWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'],'t')->from($table,'t')->orderBy('col_a','desc','t')->orderBy('col_b','asc','t');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t ORDER BY `t`.`col_a` DESC ,`t`.`col_b` ASC",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::ORDER
        ])->sql()));
    }

    public function testSelectColsFromGroupByHaving()
    {}

    public function testSelectColsFromGroupByHavingWithAlias()
    {}

    public function testSelectColsFromLimit()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->limit(10);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name LIMIT 10",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::LIMIT
        ])->sql()));
    }

    public function testSelectColsFromLimitOffset()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->limit(10)->offset(3);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name LIMIT 10 OFFSET 3",trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::LIMIT,
            Select::OFFSET
        ])->sql()));
    }

    public function testSelectColsFromWhereUnionSelectColsFromWhereWithAlias()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(2))->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `tt`.`col_a` =?',['another_value']),
                new Sql('WHERE `t`.`col_a` =?',['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $unionSelect = new Select();

        $this->select->cols(['col_a','col_b','col_c'],'t')->from($table,'t')->where('col_a','=?','some_value')->union($unionSelect->cols(['col_a','col_b','col_c'],'tt')->from($table,'tt')->where('col_a','=?','another_value'),false);

        $query = $this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE,
            Select::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t WHERE `t`.`col_a` =? UNION SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM some_table_name AS tt WHERE `tt`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testSelectColsFromWhereUnionAllSelectColsFromWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(2))->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `tt`.`col_a` =?',['another_value']),
                new Sql('WHERE `t`.`col_a` =?',['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $unionSelect = new Select();

        $this->select->cols(['col_a','col_b','col_c'],'t')->from($table,'t')->where('col_a','=?','some_value')->union($unionSelect->cols(['col_a','col_b','col_c'],'tt')->from($table,'tt')->where('col_a','=?','another_value'),true);

        $query = $this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE,
            Select::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t WHERE `t`.`col_a` =? UNION ALL SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM some_table_name AS tt WHERE `tt`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }
}