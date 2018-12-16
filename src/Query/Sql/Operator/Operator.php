<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql\Operator;

/**
 * Operator
 */
class Operator
{
    /**
     * @return Comparison
     */
    public static function comparison(): Comparison
    {
        return new Comparison();
    }

    /**
     * @return Logical
     */
    public static function logical(): Logical
    {
        return new Logical();
    }
}