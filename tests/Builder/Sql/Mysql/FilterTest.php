<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\MySql\Filter;
use QueryMule\Query\Repository\RepositoryInterface;
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


    public function setUp()
    {
        $this->query = new Query();
        $this->logical = new Logical();
    }

    public function tearDown()
    {
        $this->query = null;
        $this->logical = null;
    }

    public function testWhere()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->where('col_a', Operator::comparison()->equalTo('some_value'))->build();

        $this->assertEquals("WHERE `col_a` =?", $query->sql());
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testWhereWithAlias()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->where('t.col_a', Operator::comparison()->equalTo('some_value'))->build();

        $this->assertEquals("WHERE `t`.`col_a` =?", $query->sql());
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testWhereAndNestedWhere()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->where('col_a', Operator::comparison()->equalTo('some_value_a'))->nestedWhere(function (FilterInterface $query) {
            $query->where('col_b', Operator::comparison()->equalTo('some_value_b'));
            $query->where('col_c', Operator::comparison()->equalTo('some_value_c'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->where('col_d', Operator::comparison()->equalTo('some_value_d'));
            });
        })->build();

        $this->assertEquals("WHERE `col_a` =? AND ( `col_b` =? AND `col_c` =? AND ( `col_d` =? ) )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testNestedWhereOrNestedWhere()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->nestedWhere(function (FilterInterface $query) {
            $query->where('col_a', Operator::comparison()->equalTo('some_value_a'));
            $query->nestedWhere(function (FilterInterface $query) {
                $query->orwhere('col_b', Operator::comparison()->equalTo('some_value_b'));
            });
        })->build();

        $this->assertEquals("WHERE ( `col_a` =? OR ( `col_b` =? ) )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereAndWhere()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->where('col_a', Operator::comparison()->equalTo('some_value_a'))->where('col_b', Operator::comparison()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE `col_a` =? AND `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereOrWhere()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->where('col_a', Operator::comparison()->equalTo('some_value_a'))->orWhere('col_b', Operator::comparison()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE `col_a` =? OR `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereIn()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereIn('col_a', ['some_value_a', 'some_value_b'])->build();

        $this->assertEquals("WHERE `col_a` IN ( ?,? )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereInOrIn()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereIn('col_a', ['some_value_a', 'some_value_b'])->orWhereIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE `col_a` IN ( ?,? ) OR `col_a` IN ( ?,? )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNotIn()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereNotInAndIn()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->whereNotIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? ) AND NOT `col_a` IN ( ?,? )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNotInOrIn()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->orWhereNotIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? ) OR NOT `col_a` IN ( ?,? )", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNot()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNot('col_a', Operator::comparison()->equalTo('some_value_a'))->build();

        $this->assertEquals("WHERE NOT `col_a` =?", $query->sql());
        $this->assertEquals(['some_value_a'], $query->parameters());
    }

    public function testWhereNotAndNot()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNot('col_a', Operator::comparison()->equalTo('some_value_a'))->whereNot('col_b', Operator::comparison()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE NOT `col_a` =? AND NOT `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereBetween()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereBetween('col_a', 1, 2)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ?", $query->sql());
        $this->assertEquals([1, 2], $query->parameters());
    }

    public function testWhereBetweenOrBetween()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereBetween('col_a', 1, 2)->orWhereBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ? OR `col_b` BETWEEN ? AND ?", $query->sql());
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereBetweenAndBetween()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereBetween('col_a', 1, 2)->whereBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ? AND `col_b` BETWEEN ? AND ?", $query->sql());
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereNotBetween()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotBetween('col_a', 1, 2)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ?", $query->sql());
        $this->assertEquals([1, 2], $query->parameters());
    }

    public function testWhereNotBetweenOrNotBetween()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotBetween('col_a', 1, 2)->orWhereNotBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ? OR NOT `col_b` BETWEEN ? AND ?", $query->sql());
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereNotBetweenAndNotBetween()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotBetween('col_a', 1, 2)->whereNotBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ? AND NOT `col_b` BETWEEN ? AND ?", $query->sql());
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

//    public function testWhereExists()
//    {
//        $filter = new Filter($this->query,$this->logical);
//        $query = $filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->build([
//            Sql::WHERE
//        ]);
//
//        $this->assertEquals("WHERE EXISTS (SELECT * FROM some_table_name )", $query->sql());
//        $this->assertEquals([], $query->parameters());
//    }

//    public function testWhereNotExists()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $select = new Select();
//
//        $query = $this->filter->whereNotExists($select->cols()->from($table)->build())->build();
//        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name )", $query->sql());
//        $this->assertEquals([], $query->parameters());
//    }

//    public function testWhereExistsOrExists()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $select = new Select();
//        $select2 = new Select();
//
//        $query = $this->filter->whereExists($select->cols()->from($table)->build())->orWhereExists($select2->cols()->from($table)->build())->build();
//        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name ) OR EXISTS ( SELECT * FROM some_table_name )", $query->sql());
//        $this->assertEquals([], $query->parameters());
//    }

//    public function testWhereNotExistsOrNotExists()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $select = new Select();
//        $select2 = new Select();
//
//        $query = $this->filter->whereNotExists($select->cols()->from($table)->build())->orWhereNotExists($select2->cols()->from($table)->build())->build();
//        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name ) OR NOT EXISTS ( SELECT * FROM some_table_name )", $query->sql());
//        $this->assertEquals([], $query->parameters());
//    }

//    public function testWhereExistsAndExists()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $select = new Select();
//        $select2 = new Select();
//
//        $query = $this->filter->whereExists($select->cols()->from($table)->build())->whereExists($select2->cols()->from($table)->build())->build();
//        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name ) AND EXISTS ( SELECT * FROM some_table_name )", $query->sql());
//        $this->assertEquals([], $query->parameters());
//    }

//    public function testWhereNotExistsAndNotExists()
//    {
//        $table = $this->createMock(RepositoryInterface::class);
//        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
//
//        $select = new Select();
//        $select2 = new Select();
//
//        $query = $this->filter->whereNotExists($select->cols()->from($table)->build())->whereNotExists($select2->cols()->from($table)->build())->build();
//        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name ) AND NOT EXISTS ( SELECT * FROM some_table_name )", $query->sql());
//        $this->assertEquals([], $query->parameters());
//    }

    public function testWhereLike()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereLike('col_a', '%some_value%')->build();

        $this->assertEquals("WHERE `col_a` LIKE ?", $query->sql());
        $this->assertEquals(['%some_value%'], $query->parameters());
    }

    public function testWhereLikeOrLike()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereLike('col_a', '%some_value%')->orWhereLike('col_b','%some_value%')->build();

        $this->assertEquals("WHERE `col_a` LIKE ? OR `col_b` LIKE ?", $query->sql());
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereLikeAndLike()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereLike('col_a', '%some_value%')->whereLike('col_b', '%some_value%')->build();
        $this->assertEquals("WHERE `col_a` LIKE ? AND `col_b` LIKE ?", $query->sql());
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereNotLike()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotLike('col_a', '%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ?", $query->sql());
        $this->assertEquals(['%some_value%'], $query->parameters());
    }

    public function testWhereNotLikeOrNotLike()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotLike('col_a', '%some_value%')->orWhereNotLike('col_b','%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ? OR NOT `col_b` LIKE ?", $query->sql());
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereNotLikeAndNotLike()
    {
        $filter = new Filter($this->query,$this->logical);
        $query = $filter->whereNotLike('col_a', '%some_value%')->whereNotLike('col_b', '%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ? AND NOT `col_b` LIKE ?", $query->sql());
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }
}
