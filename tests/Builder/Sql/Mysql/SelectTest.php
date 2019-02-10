<?php
declare(strict_types=1);

namespace test\Builder\Sql\Mysql;

use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Sql\Mysql\Filter;
use Redstraw\Hooch\Builder\Sql\Mysql\OnFilter;
use Redstraw\Hooch\Builder\Sql\Mysql\Select;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Operator\Logical;
use Redstraw\Hooch\Query\Sql\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

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
     * @var SelectInterface
     */
    private $select;

    public function setUp()
    {
        $this->query = new Query(
            new Sql(),
            new Logical(),
            new Accent()
        );
        $this->query->accent()->setSymbol('`');
        $this->select = new Select($this->query);
    }

    public function tearDown()
    {
        $this->select = null;
    }

    public function testSelect()
    {
        $this->assertEquals("SELECT", trim($this->select->build([
            Sql::SELECT
        ])->string()));
    }

    public function testIgnoreAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->assertEquals("SELECT col_a ,col_b ,col_c FROM some_table_name", trim($this->select->ignoreAccent()->cols(['col_a', 'col_b', 'col_c'])->from($table)->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM
        ])->string()));
    }

    public function testSelectCols()
    {
        $this->select->cols(['col_a'])->cols(['col_b'])->cols(['col_c']);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->string()));
    }

    public function testSelectColsWithAlias()
    {
        $this->select->cols(['col_a', 'col_b', 'col_c'], 't');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->string()));
    }

    public function testSelectAllCols()
    {
        $this->select->cols([Sql::SQL_STAR]);
        $this->assertEquals("SELECT *", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->string()));
    }

    public function testSelectColsAs()
    {
        $this->select->cols(['a' => 'col_a', 'b' => 'col_b', 'c' => 'col_c']);
        $this->assertEquals("SELECT `col_a` AS a ,`col_b` AS b ,`col_c` AS c", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS
        ])->string()));
    }

    public function testSelectColsFrom()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM `some_table_name`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM
        ])->string()));
    }

    public function testSelectColsFromWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table);
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM
        ])->string()));
    }

    public function testSelectFilter()
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

        $query = $this->select->cols(['col_a'])->from($table)->filter(function($table){
            /** @var FilterInterface $this */
            $this->where('col_a', Operator::comparison()->equalTo('some_value'));
        })->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM `some_table_name` WHERE `col_a` =?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testSelectFilterByCustom()
    {
        $filter = $this->createMock(Filter::class);
        $filter->expects($this->exactly(2))->method('where');
        $filter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('WHERE `col_a` =? AND `col_b` =?', ['some_value','another_value'])
            )
        );

        $table = $this->getMockBuilder(RepositoryInterface::class)->setMethods([
            'getName',
            'getAlias',
            'select',
            'filter',
            'onFilter',
            'customFilterBy'
        ])->getMock();

        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $query = $this->select->cols(['col_a'])->from($table);

        $table->expects($this->any())->method('customFilterBy')->will(
            $this->returnValue(
                $this->select->filter(function(){
                    /** @var FilterInterface $this */
                    $this->where('col_b', Operator::comparison()->equalTo('another_value'));
                })
            )
        );

        $query = $query->filter(function(RepositoryInterface $table){
            /** @var FilterInterface $this */
            $this->where('col_a', Operator::comparison()->equalTo('some_value'));

            $table->customFilterBy();
        })->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::WHERE
        ]);

        $this->assertEquals("SELECT `col_a` FROM `some_table_name` WHERE `col_a` =? AND `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testSelectColsFromGroupBy()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->groupBy('col_a')->groupBy('col_b');
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM `some_table_name` GROUP BY `col_a` ,`col_b`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::GROUP
        ])->string()));
    }

    public function testSelectColsFromGroupByWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->groupBy('col_a', 't')->groupBy('col_b', 't');
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` GROUP BY `t`.`col_a` ,`t`.`col_b`", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::GROUP
        ])->string()));
    }

    public function testSelectColsFromOrderBy()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->orderBy('col_a', Sql::DESC)->orderBy('col_b', Sql::ASC);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM `some_table_name` ORDER BY `col_a` DESC ,`col_b` ASC", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::ORDER
        ])->string()));
    }

    public function testSelectColsFromOrderByWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->orderBy('t.col_a', Sql::DESC)->orderBy('t.col_b', Sql::ASC);
        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` ORDER BY `t`.`col_a` DESC ,`t`.`col_b` ASC", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::ORDER
        ])->string()));
    }

    public function testSelectColsFromHaving()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $this->select->cols(['t.col_a'])->from($table);
        $this->select->having('t.col_a',Operator::comparison()->equalTo('some_value'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::HAVING
        ]);

        $this->assertEquals("SELECT `t.col_a` FROM `some_table_name` AS `t` HAVING `t`.`col_a` =?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testSelectColsFromHavingWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a'])->from($table);
        $this->select->having('col_a',Operator::comparison()->equalTo('some_value'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::HAVING
        ]);

        $this->assertEquals("SELECT `col_a` FROM `some_table_name` HAVING `col_a` =?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testSelectColsFromLimit()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->limit(10);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM `some_table_name` LIMIT 10", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::LIMIT
        ])->string()));
    }

    public function testSelectColsFromLimitOffset()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table)->limit(10)->offset(3);
        $this->assertEquals("SELECT `col_a` ,`col_b` ,`col_c` FROM `some_table_name` LIMIT 10 OFFSET 3", trim($this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::LIMIT,
            Sql::OFFSET
        ])->string()));
    }

    public function testSelectColsFromUnionSelectColsFromWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));

        $unionSelect = new Select(new Query(new Sql(), new Logical(), $this->query->accent()));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->union($unionSelect->cols(['col_a', 'col_b', 'col_c'], 'tt')->from($table2), false);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` UNION SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM `another_table_name` AS `tt`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectColsFromUnionAllSelectColsFrom()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));

        $unionSelect = new Select(new Query(new Sql(), new Logical(), $this->query->accent()));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->union($unionSelect->cols(['col_a', 'col_b', 'col_c'], 'tt')->from($table2), true);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::UNION
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` UNION ALL SELECT `tt`.`col_a` ,`tt`.`col_b` ,`tt`.`col_c` FROM `another_table_name` AS `tt`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testSelectColsJoin()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->join(Sql::JOIN, $table2);

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` JOIN `another_table_name` AS `tt`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

//    public function testSelectColsJoinFilterByCustom()
//    {
//        $filter = $this->createMock(Filter::class);
//        $filter->expects($this->any())->method('where');
//        $filter->expects($this->once())->method('build')->will(
//            $this->onConsecutiveCalls(
//                new Sql('WHERE `col_a` =?', ['some_value'])
//            )
//        );
//
//        $table = $this->getMockBuilder(RepositoryInterface::class)->setMethods([
//            'getName',
//            'getAlias',
//            'select',
//            'filter',
//            'onFilter',
//            'customFilterBy'
//        ])->getMock();
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));
//        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));
//
//        $table2 = $this->getMockBuilder(RepositoryInterface::class)->setMethods([
//            'getName',
//            'getAlias',
//            'select',
//            'filter',
//            'onFilter',
//            'customFilterBy2'
//        ])->getMock();
//        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
//        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
//
//        $query = $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->join(Sql::JOIN, $table2);
//
//        $table->expects($this->once())->method('customFilterBy')->will(
//            $this->returnValue(
//                $this->select->filter(function(){
//                    /** @var FilterInterface $this */
//                    $this->where('col_a', Operator::comparison()->equalTo('value'));
//                })
//            )
//        );
//
//        $table2->expects($this->once())->method('customFilterBy2')->will(
//            $this->returnValue(
//                $this->select->filter(function(){
//                    /** @var FilterInterface $this */
//                    $this->where('col_b', Operator::comparison()->equalTo('another_value'));
//                })
//            )
//        );
//
//        $query = $query->filter(function(RepositoryInterface $table, RepositoryInterface $table2){
//            $table->customFilterBy();
//            $table2->customFilterBy2();
//        })->build([
//            Sql::SELECT,
//            Sql::COLS,
//            Sql::FROM,
//            Sql::JOIN,
//            Sql::WHERE
//        ]);
//
//        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` JOIN `another_table_name` AS `tt` WHERE `col_a` =? AND `col_b` =?", trim($query->string()));
//        $this->assertEquals(['some_value','another_value'], $query->parameters());
//    }

    public function testSelectColsJoinOn()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON t.col_a =?', ['tt.col_a'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));
        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
        $table2->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->join(Sql::JOIN, $table2)->onFilter(function(){
            /** @var OnFilterInterface $this */
            $this->on('`tt`.`col_a`', Operator::comparison()->equalTo('`tt`.`col_a`'));
        });

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` JOIN `another_table_name` AS `tt` ON t.col_a =?", trim($query->string()));
        $this->assertEquals(['tt.col_a'], $query->parameters());
    }

    public function testSelectColsLeftJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON t.col_a =?', ['tt.col_a'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
        $table2->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->leftJoin($table2, 'tt.col_a', Operator::comparison()->equalTo('tt.col_a'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` LEFT JOIN `another_table_name` AS `tt` ON t.col_a =?", trim($query->string()));
        $this->assertEquals(['tt.col_a'], $query->parameters());
    }

    public function testSelectColsRightJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON t.col_a =?', ['tt.col_a'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
        $table2->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->rightJoin($table2, 'tt.col_a', Operator::comparison()->equalTo('tt.col_a'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` RIGHT JOIN `another_table_name` AS `tt` ON t.col_a =?", trim($query->string()));
        $this->assertEquals(['tt.col_a'], $query->parameters());
    }

    public function testSelectColsInnerJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON t.col_a =?', ['tt.col_a'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
        $table2->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->innerJoin($table2, 'tt.col_a', Operator::comparison()->equalTo('tt.col_a'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` INNER JOIN `another_table_name` AS `tt` ON t.col_a =?", trim($query->string()));
        $this->assertEquals(['tt.col_a'], $query->parameters());
    }

    public function testSelectColsFullOuterJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON t.col_a =?', ['tt.col_a'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
        $table2->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')
            ->from($table)->fullOuterJoin($table2, 'tt.col_a', Operator::comparison()->equalTo('tt.col_a'));

        $query = $this->select->build([
            Sql::SELECT,
            Sql::COLS,
            Sql::FROM,
            Sql::JOIN
        ]);

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` FULL OUTER JOIN `another_table_name` AS `tt` ON t.col_a =?", trim($query->string()));
        $this->assertEquals(['tt.col_a'], $query->parameters());
    }

    public function testSelectColsAdvancedLeftJoin()
    {
        $onFilter = $this->createMock(OnFilter::class);
        $onFilter->expects($this->once())->method('on');
        $onFilter->expects($this->once())->method('build')->will(
            $this->onConsecutiveCalls(
                new Sql('ON t.col_a =? AND t.col_b =?', ['tt.col_a', 'tt.col_b'])
            )
        );

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('getAlias')->will($this->returnValue('t'));

        $table2 = $this->createMock(RepositoryInterface::class);
        $table2->expects($this->any())->method('getName')->will($this->returnValue('another_table_name'));
        $table2->expects($this->any())->method('getAlias')->will($this->returnValue('tt'));
        $table2->expects($this->any())->method('onFilter')->will($this->returnValue($onFilter));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table)->leftJoin($table2,  function(OnFilterInterface $onFilter) {
            $onFilter->on('col_a', Operator::comparison()->equalTo('tt.col_a'));
            $onFilter->on('col_b', Operator::comparison()->equalTo('tt.col_b'));
        });

        $query = $this->select->build();

        $this->assertEquals("SELECT `t`.`col_a` ,`t`.`col_b` ,`t`.`col_c` FROM `some_table_name` AS `t` LEFT JOIN `another_table_name` AS `tt` ON t.col_a =? AND t.col_b =?", trim($query->string()));
        $this->assertEquals(['tt.col_a', 'tt.col_b'], $query->parameters());
    }
}
