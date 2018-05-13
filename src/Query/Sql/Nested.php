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
     * @return string
     */
    public function nested(bool $open) : string
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

        return "";
    }

    /**
     * @return bool
     */
    public function isNested() : bool
    {
        return $this->nested;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function setNested(bool $bool)
    {
        $this->nested = $bool;

        return $this;
    }

}