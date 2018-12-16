<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\MySql\Filter;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Operator\Operator;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class FilterTest
 * @package test\Builder\Sql\Sqlite
 */
class FilterTest extends TestCase
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
     * @var Filter
     */
    private $filter;

    public function setUp()
    {
        $this->query = new Query();
        $this->logical = new Logical();
        $this->accent = new Accent();
        $this->accent->setSymbol('`');
        $this->filter = new Filter($this->query,$this->logical,$this->accent);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->logical = null;
        $this->accent = null;
        $this->filter = null;
    }

    public function testWhere()
    {
        $query = $this->filter->where('col_a', Operator::comparison()->equalTo('some_value'))->build();

        $this->assertEquals("WHERE `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testWhereWithAlias()
    {
        $query = $this->filter->where('t.col_a', Operator::comparison()->equalTo('some_value'))->build();

        $this->assertEquals("WHERE `t`.`col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testWhereAndNestedWhere()
    {
        $query = $this->filter->where('col_a', Operator::comparison()->equalTo('some_value_a'))->nestedWhere(function (FilterInterface $query) {
            $query->where('col_b', Operator::comparison()->equalTo('some_value_b'));
            $query->where('col_c', Operator::comparison()->equalTo('some_value_c'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->where('col_d', Operator::comparison()->equalTo('some_value_d'));
            });
        })->where('col_e', Operator::comparison()->equalTo('some_value_e'))->build();

        $this->assertEquals("WHERE `col_a` =? AND ( `col_b` =? AND `col_c` =? AND ( `col_d` =? ) ) AND `col_e` =?", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d', 'some_value_e'], $query->parameters());
    }

    public function testNestedWhereOrNestedWhere()
    {
        $query = $this->filter->nestedWhere(function (FilterInterface $query) {
            $query->where('col_a', Operator::comparison()->equalTo('some_value_a'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->orwhere('col_b', Operator::comparison()->equalTo('some_value_b'));
            });
        })->build();

        $this->assertEquals("WHERE ( `col_a` =? OR ( `col_b` =? ) )", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereAndWhere()
    {
        $query = $this->filter->where('col_a', Operator::comparison()->equalTo('some_value_a'))->where('col_b', Operator::comparison()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE `col_a` =? AND `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereOrWhere()
    {
        $query = $this->filter->where('col_a', Operator::comparison()->equalTo('some_value_a'))->orWhere('col_b', Operator::comparison()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE `col_a` =? OR `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereIn()
    {
        $query = $this->filter->whereIn('col_a', ['some_value_a', 'some_value_b'])->build();

        $this->assertEquals("WHERE `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereInOrIn()
    {
        $query = $this->filter->whereIn('col_a', ['some_value_a', 'some_value_b'])->orWhereIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE `col_a` IN ( ?,? ) OR `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNotIn()
    {
        $query = $this->filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereNotInAndIn()
    {
        $query = $this->filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->whereNotIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? ) AND NOT `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNotInOrIn()
    {
        $query = $this->filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->orWhereNotIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? ) OR NOT `col_a` IN ( ?,? )", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNot()
    {
        $query = $this->filter->whereNot('col_a', Operator::comparison()->equalTo('some_value_a'))->build();

        $this->assertEquals("WHERE NOT `col_a` =?", trim($query->sql()));
        $this->assertEquals(['some_value_a'], $query->parameters());
    }

    public function testWhereNotAndNot()
    {
        $query = $this->filter->whereNot('col_a', Operator::comparison()->equalTo('some_value_a'))->whereNot('col_b', Operator::comparison()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE NOT `col_a` =? AND NOT `col_b` =?", trim($query->sql()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereBetween()
    {
        $query = $this->filter->whereBetween('col_a', 1, 2)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals([1, 2], $query->parameters());
    }

    public function testWhereBetweenOrBetween()
    {
        $query = $this->filter->whereBetween('col_a', 1, 2)->orWhereBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ? OR `col_b` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereBetweenAndBetween()
    {
        $query = $this->filter->whereBetween('col_a', 1, 2)->whereBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ? AND `col_b` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereNotBetween()
    {
        $query = $this->filter->whereNotBetween('col_a', 1, 2)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals([1, 2], $query->parameters());
    }

    public function testWhereNotBetweenOrNotBetween()
    {
        $query = $this->filter->whereNotBetween('col_a', 1, 2)->orWhereNotBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ? OR NOT `col_b` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereNotBetweenAndNotBetween()
    {
        $query = $this->filter->whereNotBetween('col_a', 1, 2)->whereNotBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ? AND NOT `col_b` BETWEEN ? AND ?", trim($query->sql()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereExists()
    {
        $query = $this->filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->build([
            Sql::WHERE
        ]);

        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereNotExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereExistsOrExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->orWhereExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name ) OR EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereNotExistsOrNotExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->orWhereNotExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name ) OR NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereExistsAndExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->whereExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name ) AND EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereNotExistsAndNotExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name ) AND NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereLike()
    {
        $query = $this->filter->whereLike('col_a', '%some_value%')->build();

        $this->assertEquals("WHERE `col_a` LIKE ?", trim($query->sql()));
        $this->assertEquals(['%some_value%'], $query->parameters());
    }

    public function testWhereLikeOrLike()
    {
        $query = $this->filter->whereLike('col_a', '%some_value%')->orWhereLike('col_b','%some_value%')->build();

        $this->assertEquals("WHERE `col_a` LIKE ? OR `col_b` LIKE ?", trim($query->sql()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereLikeAndLike()
    {
        $query = $this->filter->whereLike('col_a', '%some_value%')->whereLike('col_b', '%some_value%')->build();
        $this->assertEquals("WHERE `col_a` LIKE ? AND `col_b` LIKE ?", trim($query->sql()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereNotLike()
    {
        $query = $this->filter->whereNotLike('col_a', '%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ?", trim($query->sql()));
        $this->assertEquals(['%some_value%'], $query->parameters());
    }

    public function testWhereNotLikeOrNotLike()
    {
        $query = $this->filter->whereNotLike('col_a', '%some_value%')->orWhereNotLike('col_b','%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ? OR NOT `col_b` LIKE ?", trim($query->sql()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereNotLikeAndNotLike()
    {
        $query = $this->filter->whereNotLike('col_a', '%some_value%')->whereNotLike('col_b', '%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ? AND NOT `col_b` LIKE ?", trim($query->sql()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereColEqualsAll()
    {
        $logical = new Logical();
        $comparison = new Comparison();

        $query = $this->filter->where('col_a', $comparison->equalTo($logical->all(new Sql('select * from some_table',[],false))))->build();
        $this->assertEquals("WHERE `col_a` = ALL ( select * from some_table )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereColEqualsAny()
    {
        $logical = new Logical();
        $comparison = new Comparison();

        $query = $this->filter->where('col_a', $comparison->equalTo($logical->any(new Sql('select * from some_table',[],false))))->build();
        $this->assertEquals("WHERE `col_a` = ANY ( select * from some_table )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereColEqualsSome()
    {
        $logical = new Logical();
        $comparison = new Comparison();

        $query = $this->filter->where('col_a', $comparison->equalTo($logical->some(new Sql('select * from some_table',[],false))))->build();
        $this->assertEquals("WHERE `col_a` = SOME ( select * from some_table )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereColEqualsExists()
    {
        $logical = new Logical();
        $comparison = new Comparison();

        $query = $this->filter->where('col_a', $comparison->equalTo($logical->exists(new Sql('select * from some_table',[],false))))->build();
        $this->assertEquals("WHERE `col_a` = EXISTS ( select * from some_table )", trim($query->sql()));
        $this->assertEquals([], $query->parameters());
    }
}
