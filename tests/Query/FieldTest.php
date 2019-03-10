<?php

declare(strict_types=1);

namespace test\Query;


use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Field;
use Redstraw\Hooch\Query\Sql;

/**
 * Class QueryTest
 * @package test\Query
 */
class FieldTest extends TestCase
{
    /**
     * @var Accent
     */
    private $accent;

    public function setUp()
    {
        $this->accent = new Accent();
        $this->accent->setSymbol('`');
    }

    public function testColumn()
    {
        $field = Field::column('field')->sql();

        $this->assertEquals("field", $field->queryString());
    }

    public function testColumnWithAccent()
    {
        $field = Field::column('t.field');
        $field->setAccent($this->accent);

        $this->assertEquals("`t`.`field`", $field->sql()->queryString());
    }

    public function testMin()
    {
        $field = Field::min('field')->sql();

        $this->assertEquals("MIN(field)", $field->queryString());
    }

    public function testMinWithAccent()
    {
        $field = Field::min('t.field');
        $field->setAccent($this->accent);

        $this->assertEquals("MIN(`t`.`field`)", $field->sql()->queryString());
    }

    public function testMax()
    {
        $field = Field::max('field')->sql();

        $this->assertEquals("MAX(field)", $field->queryString());
    }

    public function testMaxWithAccent()
    {
        $field = Field::max('t.field');
        $field->setAccent($this->accent);

        $this->assertEquals("MAX(`t`.`field`)", $field->sql()->queryString());
    }

    public function testCount()
    {
        $field = Field::count('field')->sql();

        $this->assertEquals("COUNT(field)", $field->queryString());
    }

    public function testCountWithAccent()
    {
        $field = Field::count('t.field');
        $field->setAccent($this->accent);

        $this->assertEquals("COUNT(`t`.`field`)", $field->sql()->queryString());
    }

    public function testAvg()
    {
        $field = Field::avg('field')->sql();

        $this->assertEquals("AVG(field)", $field->queryString());
    }

    public function testAvgWithAccent()
    {
        $field = Field::avg('t.field');
        $field->setAccent($this->accent);

        $this->assertEquals("AVG(`t`.`field`)", $field->sql()->queryString());
    }

    public function testSum()
    {
        $field = Field::sum('field')->sql();

        $this->assertEquals("SUM(field)", $field->queryString());
    }

    public function testSumWithAccent()
    {
        $field = Field::sum('t.field');
        $field->setAccent($this->accent);

        $this->assertEquals("SUM(`t`.`field`)", $field->sql()->queryString());
    }
}
