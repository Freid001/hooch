<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql;

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
    public static function column(string $column)
    {
        return new Column($column);
    }

    /**
     * @param String $column
     * @return Avg
     */
    public static function avg(String $column)
    {
        return new Avg($column);
    }

    /**
     * @param String $column
     * @return Count
     */
    public static function count(String $column)
    {
        return new Count($column);
    }

    /**
     * @param String $column
     * @return Max
     */
    public static function max(String $column)
    {
        return new Max($column);
    }

    /**
     * @param String $column
     * @return min
     */
    public static function min(String $column)
    {
        return new Min($column);
    }

    /**
     * @param String $column
     * @return Sum
     */
    public static function sum(String $column)
    {
        return new Sum($column);
    }
}
