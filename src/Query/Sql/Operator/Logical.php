<?php

namespace QueryMule\Query\Sql\Operator;


use QueryMule\Query\Sql\Accent;
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

    public function setNested(bool $bool)
    {
        $this->nested = $bool;
    }

    public function in(array $values = [])
    {
        $sql = Sql::IN;
        $sql .= Sql::SQL_SPACE;
        $sql .= implode(Sql::SQL_SPACE, [
            Sql::SQL_BRACKET_OPEN,
            implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)),
            Sql::SQL_BRACKET_CLOSE
        ]);

        $this->sql = new Sql($sql, $values);

        return $this;
    }

    public function not($column, Comparison $comparison)
    {
        $value = [];
        if (!empty($comparison)) {
            $value = $comparison->build()->parameters();
        }

        $sql = Sql::NOT;
        $sql .= Sql::SQL_SPACE;
        $sql .= $this->nest(true);
        $sql .= $column;
        $sql .= Sql::SQL_SPACE;
        $sql .= ($comparison instanceof Comparison) ? $comparison->build()->sql() : "";

        $this->sql = new Sql($sql,$value);

        return $this;
    }

    /**
     * @param string $column
     * @param null|Comparison $comparison
     * @return Logical
     */
    public function and ($column, ?Comparison $comparison): Logical
    {
        $value = [];
        if (!empty($comparison)) {
            $value = $comparison->build()->parameters();
        }

        $sql = Sql:: AND;
        $sql .= Sql::SQL_SPACE;
        $sql .= $this->nest(true);
        $sql .= is_string($column) ? $column.Sql::SQL_SPACE : "";
        $sql .= ($comparison instanceof Comparison) ? $comparison->build()->sql() : "";

        $this->sql = new Sql($sql,$value);

        return $this;
    }

    /**
     * @param string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Logical
     */
    public function or ($column, ?Comparison $comparison, ?Logical $logical): Logical
    {
        $value = [];
        if (!empty($comparison)) {
            $value = array_merge($value, $comparison->build()->parameters());
        }

        if (!empty($logical)) {
            $value = array_merge($value, $logical->build()->parameters());
        }

        $sql = Sql:: OR;
        $sql .= Sql::SQL_SPACE;
        $sql .= $this->nest(true);
        $sql .= is_string($column) ? $column.Sql::SQL_SPACE : "";
        $sql .= ($comparison instanceof Comparison) ? $comparison->build()->sql() : "";
        $sql .= ($logical instanceof Logical) ? $logical->build()->sql() : "";

        $this->sql = new Sql($sql, $value);

        return $this;
    }

    /**
     * @return Sql
     */
    public function build(): Sql
    {
        return $this->sql;
    }
}
