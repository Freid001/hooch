<?php
declare(strict_types=1);

namespace test\Builder\Sql\Mysql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Builder\Sql\Mysql\OnFilter;
use QueryMule\Builder\Sql\Mysql\Select;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Operator\Operator;
use QueryMule\Query\Sql\Query;
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
     * @var Query
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * @var Select
     */
    private $select;

    public function setUp()
    {
        $this->query = new Query();
        $this->logical = new Logical();
        $this->accent = new Accent();
        $this->accent->setSymbol('`');
        $this->select = new Select($this->query, $this->logical, $this->accent);
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
        $this->assertEquals("SELECT col_a ,col_b ,col_c", trim($this->select->ignoreAccent()->cols(['col_a', 'col_b', 'col_c'])->build([
            Sql::SELECT,
            Sql::COLS
        ])->sql()));
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

    public function testSelectFilterWhere()
    {
        $filter = $this->createMock(Filter::class);
        $filter->expects($this->once())->method('where');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =?', ['some_value'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $this->select->cols(['col_a'])->from($table);
        $this->select->filter()->where('col_a', Operator::comparison()->equalTo('some_value'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM some_table_name WHERE `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
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

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->orderBy('col_a',Sql::DESC)->orderBy('col_b',Sql::ASC);
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

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->orderBy('t.col_a',Sql::DESC)->orderBy('t.col_b',Sql::ASC);
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

    public function testSelectColsFromUnionSelectColsFromWithAlias()
    {
        $filter = $this->createMock(Filter::class);
        $filter->expects($this->once())->method('build');

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $unionSelect = new Select(new Query(), new Logical(), $this->accent);

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->union($unionSelect->cols(['col_a', 'col_b', 'col_c'], 'tt')->from($table, 'tt'), false);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t UNION SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM some_table_name AS tt", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectColsFromUnionAllSelectColsFrom()
    {
        $filter = $this->createMock(Filter::class);
        $filter->expects($this->once())->method('build');

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $unionSelect = new Select(new Query(), new Logical(), $this->accent);

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->union($unionSelect->cols(['col_a', 'col_b', 'col_c'], 'tt')->from($table, 'tt'), true);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t UNION ALL SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM some_table_name AS tt", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectColsJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->join(Sql::JOIN, $table, 'tt');

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t JOIN some_table_name AS tt", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectColsJoinOn()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON `t`.`col_a` =?', ['`tt`.`col_a`'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->join(Sql::JOIN, $table, 'tt');
        $this->select->onFilter()->on('`tt`.`col_a`', Operator::comparison()->equalTo('`tt`.`col_a`'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t JOIN some_table_name AS tt ON `t`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['`tt`.`col_a`'], $query->parameters());
    }

    public function testSelectColsLeftJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON `t`.`col_a` =?', ['`tt`.`col_a`'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->leftJoin($table, 'tt', '`tt`.`col_a`', Operator::comparison()->equalTo('`tt`.`col_a`'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t LEFT JOIN some_table_name AS tt ON `t`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['`tt`.`col_a`'], $query->parameters());
    }

    public function testSelectColsRightJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON `t`.`col_a` =?', ['`tt`.`col_a`'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->rightJoin($table, 'tt', '`tt`.`col_a`', Operator::comparison()->equalTo('`tt`.`col_a`'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t RIGHT JOIN some_table_name AS tt ON `t`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['`tt`.`col_a`'], $query->parameters());
    }

    public function testSelectColsInnerJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON `t`.`col_a` =?', ['`tt`.`col_a`'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->innerJoin($table, 'tt', '`tt`.`col_a`', Operator::comparison()->equalTo('`tt`.`col_a`'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t INNER JOIN some_table_name AS tt ON `t`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['`tt`.`col_a`'], $query->parameters());
    }

    public function testSelectColsFullOuterJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON `t`.`col_a` =?', ['`tt`.`col_a`'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->fullOuterJoin($table, 'tt', '`tt`.`col_a`', Operator::comparison()->equalTo('`tt`.`col_a`'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t FULL OUTER JOIN some_table_name AS tt ON `t`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['`tt`.`col_a`'], $query->parameters());
    }



//    public function testSelectColsLeftJoinOn()
//    {
//        $filter = $this->createMock(Filter::class);
//        $filter->expects($this->any())->method('build')->will(
//            $this->onConsecutiveCalls(
//                new Sql('WHERE `tt`.`col_a` =?', ['another_value'])
//            )
//        );
//
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));
//
//        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't')->join(Sql::JOIN_LEFT, $table,  function(On $query){
//
//            $query->on('tt.col_c',Operator::comparison()->equalTo('col_b'));
//
//            $query->nestedWhere(function (Filter $filter){
//                $filter->where('tt.col_a',Operator::comparison()->equalTo('1'));
//                $filter->where('tt.col_b',Operator::comparison()->equalTo('1'));
//            });
//
//            $query->where('tt.col_c',Operator::comparison()->equalTo('abc'));
//
//        }, 'tt');
//
//        $query = $this->select->build();
//
//        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM some_table_name AS t LEFT JOIN some_table_name AS tt OR ( `1` =? JOIN `2` OR ( `1` =? ) JOIN `col_d` OR ( `1` =? WHERE `tt`.`col_a` =?", trim($query->sql()));
//        $this->assertEquals(['some_value', 'another_value'], $query->parameters());
//    }
}
