<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query;

use Redstraw\Hooch\Query\Field\Avg;
use Redstraw\Hooch\Query\Field\Column;
use Redstraw\Hooch\Query\Field\Count;
use Redstraw\Hooch\Query\Field\Max;
use Redstraw\Hooch\Query\Sql\Min;
use Redstraw\Hooch\Query\Sql\Sum;

/**
 * Class Field
 * @package Redstraw\Hooch\Query\Sql
 */
class Field
{
    /**
     * @param string $column
     * @return Column
     */
    public static function column(string $column): Column
    {
        return new Column($column);
    }

    /**
     * @param string $column
     * @return Avg
     */
    public static function avg(String $column): Avg
    {
        return new Avg($column);
    }

    /**
     * @param string $column
     * @return Count
     */
    public static function count(String $column): Count
    {
        return new Count($column);
    }

    /**
     * @param string $column
     * @return Max
     */
    public static function max(String $column): Max
    {
        return new Max($column);
    }

    /**
     * @param string $column
     * @return Min
     */
    public static function min(String $column): Min
    {
        return new Min($column);
    }

    /**
     * @param string $column
     * @return Sum
     */
    public static function sum(String $column): Sum
    {
        return new Sum($column);
    }
}
