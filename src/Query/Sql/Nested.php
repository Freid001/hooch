<?php


namespace QueryMule\Query\Sql;

/**
 * Trait Nested
 * @package QueryMule\Query\Sql
 */
trait Nested
{
    /**
     * @var bool
     */
    protected $nested = false;

    /**
     * @param bool $open
     * @return null|string
     */
    private function nest(bool $open)
    {
        $bracket = Sql::SQL_BRACKET_CLOSE;
        if ($this->nested) {
            if ($open) {
                $bracket = Sql::SQL_BRACKET_OPEN;
                $bracket .= Sql::SQL_SPACE;
            }

            $this->nested = false;

            return $bracket;
        }

        return null;
    }
}