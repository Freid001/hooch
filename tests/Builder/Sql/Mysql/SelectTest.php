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
        $this->assertEquals("SELECT", trim($this->select->build([
            Sql::SELECT
        ])->sql()));
    }

    public function testIgnoreAlias()
    {
        $this->assertEquals("SELECT col_a ,col_b ,col_c", $this->select->ignoreAccent()->cols(['col_a', 'col_b', 'col_c'])->build([
            Sql::SELECT,
            Sql::COLS
        ])->sql());
    }

    public function testSelectCols()
    {
        $this->select->cols(['col_a'])->cols(['col_b'])->cols(['col_c']);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->sql()));
    }

    public function testSelectColsWithAlias()
    {
        $this->select->cols(['col_a', 'col_b', 'col_c'], 't');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->sql()));
    }

    public function testSelectAllCols()
    {
        $this->select->cols([Sql::SQL_STAR]);
        $this->assertEquals("SELECT *", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->sql()));
    }

    public function testSelectColsAs()
    {
        $this->select->cols(['a' => 'col_a', 'b' => 'col_b', 'c' => 'col_c']);
        $this->assertEquals("SELECT `col_a` AS a ,`col_b` AS b ,`col_c` AS c", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->sql()));
    }

    public function testSelectColsFrom()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM
        ])->sql()));
    }

    public function testSelectColsFromWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM
        ])->sql()));
    }

    public function testSelectWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('where');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =?', ['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a'])->from($table)->where('col_a', $this->select->comparison()->equalTo('some_value'))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM some_table_name WHERE `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testSelectWhereNot()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('whereNot');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT `col_a` =?', ['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a'])->from($table)->whereNot('col_a', $this->select->comparison()->equalTo('some_value'))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM some_table_name WHERE NOT `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testSelectWhereNotOrWhereNot()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('whereNot');
        $filter->expects($this->once())->method('orWhereNot');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT `col_a` =? OR NOT `col_a` =?', ['some_value','another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a'])->from($table)->whereNot('col_a', $this->select->comparison()->equalTo('some_value'))->orWhereNot('col_a', $this->select->comparison()->equalTo('another_value'))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM some_table_name WHERE NOT `col_a` =? OR NOT `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testSelectWhereOrWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('where');
        $filter->expects($this->once())->method('orWhere');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =? OR `col_b` =?', ['some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->where('col_a', $this->select->comparison()->equalTo('some_value'))->orWhere('col_b', $this->select->comparison()->equalTo('another_value'))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` =? OR `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereAndWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(2))->method('where');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =? AND `col_b` =?', ['some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->where('col_a', $this->select->comparison()->equalTo('some_value'))->where('col_b', $this->select->comparison()->equalTo('another_value'))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` =? AND `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereIn()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereIn');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` IN ( ?,? )', ['some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereIn('col_a',['some_value','another_value'])->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereInOrWhereIn()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereIn');
        $filter->expects($this->exactly(1))->method('orWhereIn');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` IN ( ?,? ) OR `col_a` IN ( ?,? )', ['some_value', 'another_value', 'another_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereIn('col_a',['some_value','another_value'])->orWhereIn('col_a',['another_value','another_value'])->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` IN ( ?,? ) OR `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value', 'another_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereNotIn()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereNotIn');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT `col_a` IN ( ?,? )', ['some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereNotIn('col_a',['some_value','another_value'])->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE NOT `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereNotInOrWhereNotIn()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereNotIn');
        $filter->expects($this->exactly(1))->method('orWhereNotIn');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT `col_a` IN ( ?,? ) OR NOT `col_a` IN ( ?,? )', ['some_value', 'another_value','another_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereNotIn('col_a',['some_value','another_value'])->orWhereNotIn('col_a',['another_value','another_value'])->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE NOT `col_a` IN ( ?,? ) OR NOT `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value','another_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereBetween()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereBetween');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` BETWEEN ? AND ?', ['some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereBetween('col_a','some_value','another_value')->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereBetweenOrWhereBetween()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereBetween');
        $filter->expects($this->exactly(1))->method('orWhereBetween');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` BETWEEN ? AND ? OR `col_a` BETWEEN ? AND ?', ['some_value', 'another_value','some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereBetween('col_a','some_value','another_value')->orWhereBetween('col_a','some_value','another_value')->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE `col_a` BETWEEN ? AND ? OR `col_a` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value','some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereNotBetween()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereNotBetween');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT `col_a` BETWEEN ? AND ?', ['some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereNotBetween('col_a','some_value','another_value')->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE NOT `col_a` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectNotWhereBetweenOrNotWhereBetween()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereNotBetween');
        $filter->expects($this->exactly(1))->method('orWhereNotBetween');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT `col_a` BETWEEN ? AND ? OR NOT `col_a` BETWEEN ? AND ?', ['some_value', 'another_value','some_value', 'another_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereNotBetween('col_a','some_value','another_value')->orWhereNotBetween('col_a','some_value','another_value')->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE NOT `col_a` BETWEEN ? AND ? OR NOT `col_a` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value','some_value', 'another_value'], $query->parameters());
    }

    public function testSelectWhereExists()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereExists');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE EXISTS ( SELECT * FROM some_table_name )', [])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereExists(new Sql(null,[]))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectNotWhereExists()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereNotExists');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT EXISTS ( SELECT * FROM some_table_name )', [])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereNotExists(new Sql(null,[]))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectWhereExistsOrWhereExists()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereExists');
        $filter->expects($this->exactly(1))->method('orWhereExists');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE EXISTS ( SELECT * FROM some_table_name ) OR EXISTS ( SELECT * FROM some_table_name )', [])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereExists(new Sql(null,[]))->orWhereExists(new Sql(null,[]))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE EXISTS ( SELECT * FROM some_table_name ) OR EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectNotWhereExistsOrNotWhereExists()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(1))->method('whereNotExists');
        $filter->expects($this->exactly(1))->method('orWhereNotExists');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE NOT EXISTS ( SELECT * FROM some_table_name ) OR NOT EXISTS ( SELECT * FROM some_table_name )', [])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a', 'col_b'])->from($table)->whereNotExists(new Sql(null,[]))->orWhereNotExists(new Sql(null,[]))->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` ,`col_b` FROM some_table_name WHERE NOT EXISTS ( SELECT * FROM some_table_name ) OR NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectColsFromGroupBy()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->groupBy('col_a')->groupBy('col_b');
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name GROUP BY `col_a` ,`col_b`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::GROUP
        ])->sql()));
    }

    public function testSelectColsFromGroupByWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->groupBy('col_a', 't')->groupBy('col_b', 't');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t GROUP BY `t`.`col_a` ,`t`.`col_b`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::GROUP
        ])->sql()));
    }

    public function testSelectColsFromOrderBy()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->orderBy('col_a')->orderBy('col_b', 'asc');
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name ORDER BY `col_a` DESC ,`col_b` ASC", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::ORDER
        ])->sql()));
    }

    public function testSelectColsFromOrderByWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->orderBy('col_a', 'desc', 't')->orderBy('col_b', 'asc', 't');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t ORDER BY `t`.`col_a` DESC ,`t`.`col_b` ASC", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::ORDER
        ])->sql()));
    }

//    public function testSelectColsFromGroupByHaving()
//    {
//    }
//
//    public function testSelectColsFromGroupByHavingWithAlias()
//    {
//    }

    public function testSelectColsFromLimit()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->limit(10);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name LIMIT 10", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::LIMIT
        ])->sql()));
    }

    public function testSelectColsFromLimitOffset()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->limit(10)->offset(3);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM some_table_name LIMIT 10 OFFSET 3", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::LIMIT,
            Sql::OFFSET
        ])->sql()));
    }

    public function testSelectColsFromWhereUnionSelectColsFromWhereWithAlias()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(2))->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `tt`.`col_a` =?', ['another_value']),
                new Sql('WHERE `t`.`col_a` =?', ['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $unionSelect = new Select();

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->where('col_a', $this->select->comparison()->equalTo('some_value'))->union($unionSelect->cols(['col_a', 'col_b', 'col_c'], 'tt')->from($table, 'tt')->where('col_a', $this->select->comparison()->equalTo('another_value')), false);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE,
            Sql::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t WHERE `t`.`col_a` =? UNION SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM some_table_name AS tt WHERE `tt`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

    public function testSelectColsFromWhereUnionAllSelectColsFromWhere()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->exactly(2))->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `tt`.`col_a` =?', ['another_value']),
                new Sql('WHERE `t`.`col_a` =?', ['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $unionSelect = new Select();

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->where('col_a', $this->select->comparison()->equalTo('some_value'))->union($unionSelect->cols(['col_a', 'col_b', 'col_c'], 'tt')->from($table, 'tt')->where('col_a', $this->select->comparison()->equalTo('another_value')), true);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE,
            Sql::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t WHERE `t`.`col_a` =? UNION ALL SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM some_table_name AS tt WHERE `tt`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
    }

//    public function test()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->leftJoin(['tt'=>$table], 'col_a','=','col_b');
//
//        $query = $this->select->whereNot('col_a',)->build([
//            Sql::SELECT,
//            Sql::COLS,
//            Sql::FROM,
//        ]);
//
//         $this->assertEquals("", trim($query->sql()));
//         $this->assertEquals([], $query->parameters());
//    }

//    public function test2()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->leftJoin(['tt'=>$table], function(Select $query){
//            $query->on('col_c','=','col_b');
//
//        });
//
//        $query = $this->select->build([
//            Sql::SELECT,
//            Sql::COLS,
//            Sql::FROM,
//            Sql::JOIN
//        ]);
//
//        $this->assertEquals("", trim($query->sql()));
//        $this->assertEquals([], $query->parameters());
//    }
}

