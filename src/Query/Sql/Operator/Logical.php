<?php

namespace QueryMule\Sql\Operator;


use QueryMule\Query\Sql\Nested;
use QueryMule\Query\Sql\Sql;

/**
 * Class Comparison
 * @package QueryMule\Builder\Sql\Generic
 */
class Logical
{
    use Nested;

    /**
     * @var Sql
     */
    private $sql;

    /**
     * @param $sql
     */
    private function __construct(Sql $sql)
    {
        $this->sql = $sql;
    }

    public static function in(array $values = [])
    {
        $sql = Sql::IN;
        $sql .= Sql::SQL_SPACE;
        $sql .= implode(Sql::SQL_SPACE, [
            Sql::SQL_BRACKET_OPEN,
            implode( ",", array_fill(0, count($values), "?")),
            Sql::SQL_BRACKET_CLOSE
        ]);

        return new self(new Sql($sql, $values));
    }

    public static function not($column, Comparison $comparison)
    {
        $sql = Sql::NOT;
        $sql .= Sql::SQL_SPACE;
        //$sql .= $this->nest(true);
        $sql .= implode(Sql::SQL_SPACE, [
            $column,
            $comparison->build()
        ]);

        return new self (new Sql($sql));
    }

    public static function and()
    {
        return new self (new Sql(null));
    }

    public static function or()
    {}

    public function build()
    {
        return $this->sql;
    }
}