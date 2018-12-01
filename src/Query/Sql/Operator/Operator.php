<?php

namespace QueryMule\Query\Sql\Operator;

/**
 * Operator
 */
class Operator
{
    /**
     * @return Comparison#
     */
    public static function comparison()
    {
        return new Comparison();
    }

    /**
     * @return Logical
     */
    public static function logical()
    {
        return new Logical();
    }
}