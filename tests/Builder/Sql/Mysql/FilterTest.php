<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Sql\MySql\Filter;
use Redstraw\Hooch\Query\Common\Operator\Comparison;
use Redstraw\Hooch\Query\Common\Operator\Logical;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

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
     * @var Operator
     */
    private $operator;

    /**
     * @var FilterInterface
     */
    private $filter;

    public function setUp()
    {
        $this->query = new Query(
            new Sql(),
            new Accent()
        );
        $this->query->accent()->setSymbol('`');
        $this->operator = new Operator(
            new Comparison(
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Column(new Sql(), $this->query->accent())
            ),
            new Logical(
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Column(new Sql(), $this->query->accent())
            )
        );
        $this->filter = new Filter($this->query, $this->operator);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->operator = null;
        $this->filter = null;
    }

    public function testWhere()
    {
        $query = $this->filter->where('col_a', $this->operator->comparison()->param()->equalTo('some_value'))->build();

        $this->assertEquals("WHERE `col_a` =?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testWhereWithAlias()
    {
        $query = $this->filter->where('t.col_a', $this->operator->comparison()->param()->equalTo('some_value'))->build();

        $this->assertEquals("WHERE `t`.`col_a` =?", trim($query->string()));
        $this->assertEquals(['some_value'], $query->parameters());
    }

    public function testWhereAndNestedWhereAndNestedWhere()
    {
        $operator = $this->operator;
        $query = $this->filter->where('col_a', $this->operator->comparison()->param()->equalTo('some_value_a'))->nestedWhere(function () use ($operator) {
            /** @var FilterInterface $this */
            $this->where('col_b', $operator->comparison()->param()->equalTo('some_value_b'));

            /** @var FilterInterface $this */
            $this->where('col_c', $operator->comparison()->param()->equalTo('some_value_c'));

            /** @var FilterInterface $this */
            $this->nestedWhere(function () use ($operator) {
                /** @var FilterInterface $this */
                $this->where('col_d', $operator->comparison()->param()->equalTo('some_value_d'));
            });
        })->where('col_e', $this->operator->comparison()->param()->equalTo('some_value_e'))->build();

        $this->assertEquals("WHERE `col_a` =? AND ( `col_b` =? AND `col_c` =? AND ( `col_d` =? ) ) AND `col_e` =?", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d', 'some_value_e'], $query->parameters());
    }

    public function testNestedWhereOrNestedWhere()
    {
        $operator = $this->operator;
        $query = $this->filter->nestedWhere(function () use ($operator) {
            /** @var FilterInterface $this */
            $this->where('col_a', $operator->comparison()->param()->equalTo('some_value_a'));

            /** @var FilterInterface $this */
            $this->nestedWhere(function () use ($operator) {
                /** @var FilterInterface $this */
                $this->orwhere('col_b', $operator->comparison()->param()->equalTo('some_value_b'));
            });
        })->build();

        $this->assertEquals("WHERE ( `col_a` =? OR ( `col_b` =? ) )", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereAndWhere()
    {
        $query = $this->filter->where('col_a', $this->operator->comparison()->param()->equalTo('some_value_a'))->where('col_b', $this->operator->comparison()->param()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE `col_a` =? AND `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereOrWhere()
    {
        $query = $this->filter->where('col_a', $this->operator->comparison()->param()->equalTo('some_value_a'))->orWhere('col_b', $this->operator->comparison()->param()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE `col_a` =? OR `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereIn()
    {
        $query = $this->filter->whereIn('col_a', ['some_value_a', 'some_value_b'])->build();

        $this->assertEquals("WHERE `col_a` IN ( ?,? )", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereInOrIn()
    {
        $query = $this->filter->whereIn('col_a', ['some_value_a', 'some_value_b'])->orWhereIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE `col_a` IN ( ?,? ) OR `col_a` IN ( ?,? )", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNotIn()
    {
        $query = $this->filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? )", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereNotInAndIn()
    {
        $query = $this->filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->whereNotIn('col_b', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? ) AND NOT `col_b` IN ( ?,? )", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNotInOrIn()
    {
        $query = $this->filter->whereNotIn('col_a', ['some_value_a', 'some_value_b'])->orWhereNotIn('col_a', ['some_value_c', 'some_value_d'])->build();

        $this->assertEquals("WHERE NOT `col_a` IN ( ?,? ) OR NOT `col_a` IN ( ?,? )", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b', 'some_value_c', 'some_value_d'], $query->parameters());
    }

    public function testWhereNot()
    {
        $query = $this->filter->whereNot('col_a', $this->operator->comparison()->param()->equalTo('some_value_a'))->build();

        $this->assertEquals("WHERE NOT `col_a` =?", trim($query->string()));
        $this->assertEquals(['some_value_a'], $query->parameters());
    }

    public function testWhereNotAndNot()
    {
        $query = $this->filter->whereNot('col_a', $this->operator->comparison()->param()->equalTo('some_value_a'))->whereNot('col_b', $this->operator->comparison()->param()->equalTo('some_value_b'))->build();

        $this->assertEquals("WHERE NOT `col_a` =? AND NOT `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value_a', 'some_value_b'], $query->parameters());
    }

    public function testWhereBetween()
    {
        $query = $this->filter->whereBetween('col_a', 1, 2)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ?", trim($query->string()));
        $this->assertEquals([1, 2], $query->parameters());
    }

    public function testWhereBetweenOrBetween()
    {
        $query = $this->filter->whereBetween('col_a', 1, 2)->orWhereBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ? OR `col_b` BETWEEN ? AND ?", trim($query->string()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereBetweenAndBetween()
    {
        $query = $this->filter->whereBetween('col_a', 1, 2)->whereBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE `col_a` BETWEEN ? AND ? AND `col_b` BETWEEN ? AND ?", trim($query->string()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereNotBetween()
    {
        $query = $this->filter->whereNotBetween('col_a', 1, 2)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ?", trim($query->string()));
        $this->assertEquals([1, 2], $query->parameters());
    }

    public function testWhereNotBetweenOrNotBetween()
    {
        $query = $this->filter->whereNotBetween('col_a', 1, 2)->orWhereNotBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ? OR NOT `col_b` BETWEEN ? AND ?", trim($query->string()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereNotBetweenAndNotBetween()
    {
        $query = $this->filter->whereNotBetween('col_a', 1, 2)->whereNotBetween('col_b', 3, 4)->build();

        $this->assertEquals("WHERE NOT `col_a` BETWEEN ? AND ? AND NOT `col_b` BETWEEN ? AND ?", trim($query->string()));
        $this->assertEquals([1, 2, 3, 4], $query->parameters());
    }

    public function testWhereExists()
    {
        $query = $this->filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->build([
            Sql::WHERE
        ]);

        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereNotExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereExistsOrExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->orWhereExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name ) OR EXISTS ( SELECT * FROM some_table_name )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereNotExistsOrNotExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->orWhereNotExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name ) OR NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereExistsAndExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereExists(new Sql(" SELECT * FROM some_table_name"))->whereExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE EXISTS ( SELECT * FROM some_table_name ) AND EXISTS ( SELECT * FROM some_table_name )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereNotExistsAndNotExists()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->filter->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->whereNotExists(new Sql(" SELECT * FROM some_table_name"))->build();
        $this->assertEquals("WHERE NOT EXISTS ( SELECT * FROM some_table_name ) AND NOT EXISTS ( SELECT * FROM some_table_name )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereLike()
    {
        $query = $this->filter->whereLike('col_a', '%some_value%')->build();

        $this->assertEquals("WHERE `col_a` LIKE ?", trim($query->string()));
        $this->assertEquals(['%some_value%'], $query->parameters());
    }

    public function testWhereLikeOrLike()
    {
        $query = $this->filter->whereLike('col_a', '%some_value%')->orWhereLike('col_b','%some_value%')->build();

        $this->assertEquals("WHERE `col_a` LIKE ? OR `col_b` LIKE ?", trim($query->string()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereLikeAndLike()
    {
        $query = $this->filter->whereLike('col_a', '%some_value%')->whereLike('col_b', '%some_value%')->build();
        $this->assertEquals("WHERE `col_a` LIKE ? AND `col_b` LIKE ?", trim($query->string()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereNotLike()
    {
        $query = $this->filter->whereNotLike('col_a', '%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ?", trim($query->string()));
        $this->assertEquals(['%some_value%'], $query->parameters());
    }

    public function testWhereNotLikeOrNotLike()
    {
        $query = $this->filter->whereNotLike('col_a', '%some_value%')->orWhereNotLike('col_b','%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ? OR NOT `col_b` LIKE ?", trim($query->string()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereNotLikeAndNotLike()
    {
        $query = $this->filter->whereNotLike('col_a', '%some_value%')->whereNotLike('col_b', '%some_value%')->build();
        $this->assertEquals("WHERE NOT `col_a` LIKE ? AND NOT `col_b` LIKE ?", trim($query->string()));
        $this->assertEquals(['%some_value%','%some_value%'], $query->parameters());
    }

    public function testWhereColEqualsAll()
    {
        $all = $this->operator->logical()->sql()->all(new Sql('select * from some_table',[],false))->build();
        $query = $this->filter->where('col_a', $this->operator->comparison()->sql()->equalTo($all, false))->build();
        $this->assertEquals("WHERE `col_a` = ALL ( select * from some_table )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereColEqualsAny()
    {
        $any = $this->operator->logical()->sql()->any(new Sql('select * from some_table',[],false))->build();
        $query = $this->filter->where('col_a', $this->operator->comparison()->sql()->equalTo($any,false))->build();
        $this->assertEquals("WHERE `col_a` = ANY ( select * from some_table )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereColEqualsSome()
    {
        $some = $this->operator->logical()->sql()->some(new Sql('select * from some_table',[],false))->build();
        $query = $this->filter->where('col_a', $this->operator->comparison()->sql()->equalTo($some, false))->build();
        $this->assertEquals("WHERE `col_a` = SOME ( select * from some_table )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testWhereColEqualsExists()
    {
        $exists = $this->operator->logical()->sql()->exists(new Sql('select * from some_table',[],false))->build();
        $query = $this->filter->where('col_a', $this->operator->comparison()->sql()->equalTo($exists, false))->build();
        $this->assertEquals("WHERE `col_a` = EXISTS ( select * from some_table )", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }
}
