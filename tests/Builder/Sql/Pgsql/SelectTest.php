<?php
declare(strict_types=1);

namespace test\Builder\Sql\Pgsql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\Pgsql\Filter;
use QueryMule\Builder\Sql\Pgsql\Select;
use QueryMule\Query\Repository\RepositoryInterface;
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

    /**
     * @var RepositoryInterface
     */
    private $table;

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
            Select::SELECT
        ])->sql()));
    }

    public function testIgnoreAlias()
    {
        $this->assertEquals("SELECT col_a ,col_b ,col_c", $this->select->ignoreAccent()->cols(['col_a', 'col_b', 'col_c'])->build([
            Select::SELECT,
            Select::COLS
        ])->sql());
    }

    public function testSelectCols()
    {
        $this->select->cols(['col_a', 'col_b', 'col_c']);
        $this->assertEquals("SELECT 'col_a' ,'col_b' ,'col_c'", trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectColsWithAlias()
    {
        $this->select->cols(['col_a', 'col_b', 'col_c'], 't');
        $this->assertEquals("SELECT 't'.'col_a' ,'t'.'col_b' ,'t'.'col_c'", trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectAllCols()
    {
        $this->select->cols([SelectInterface::SQL_STAR]);
        $this->assertEquals("SELECT *", trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectColsAs()
    {
        $this->select->cols(['a' => 'col_a', 'b' => 'col_b', 'c' => 'col_c']);
        $this->assertEquals("SELECT 'col_a' AS a ,'col_b' AS b ,'col_c' AS c", trim($this->select->build([
            Select::SELECT,
            Select::COLS
        ])->sql()));
    }

    public function testSelectColsFrom()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'])->from($table);
        $this->assertEquals("SELECT 'col_a' ,'col_b' ,'col_c' FROM some_table_name", trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM
        ])->sql()));
    }

    public function testSelectColsFromWithAlias()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $this->select->cols(['col_a', 'col_b', 'col_c'], 't')->from($table, 't');
        $this->assertEquals("SELECT 't'.'col_a' ,'t'.'col_b' ,'t'.'col_c' FROM some_table_name AS t", trim($this->select->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM
        ])->sql()));
    }

    public function testSelectWhereFilterCall()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('where');
        $filter->expects($this->once())->method('build');

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $this->select->cols(['col_a'])->from($table)->where('col_a','=?','some_value')->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE
        ]);
    }

    public function testSelectOrWhereFilterCall()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('orWhere');
        $filter->expects($this->once())->method('build');

        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('filter')->will($this->returnValue($filter));

        $this->select->cols(['col_a'])->from($table)->orWhere('col_a','=?','some_value')->build([
            Select::SELECT,
            Select::COLS,
            Select::FROM,
            Select::WHERE
        ]);
    }
}