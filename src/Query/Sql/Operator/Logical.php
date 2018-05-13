<?php

namespace QueryMule\Query\Sql\Operator;


use QueryMule\Query\Sql\Nested;
use QueryMule\Query\Sql\Sql;

/**
 * Class HasLogicalOperator
 * @package QueryMule\Query\Sql\Operator
 */
class Logical
{
    use Nested;

    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var string
     */
    private $operator;

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function all(Sql $subQuery): Logical
    {
        $this->sql = $this->operatorWithSubQuery(Sql::ALL, $subQuery);

        $this->operator = Sql::ALL;

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Logical
     */
    public function and ($column, ?Comparison $comparison, ?Logical $logical): Logical
    {
        $this->sql = $this->operatorWithColumn(Sql:: AND, $column, $comparison, $logical);

        $this->operator = Sql:: AND;

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function any(Sql $subQuery): Logical
    {
        $this->sql = $this->operatorWithSubQuery(Sql::ANY, $subQuery);

        $this->operator = Sql::ANY;

        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @return Logical
     */
    public function between($from, $to): Logical
    {
        $sql = Sql::BETWEEN;
        $sql .= Sql::SQL_SPACE;
        $sql .= Sql::SQL_QUESTION_MARK;
        $sql .= Sql::SQL_SPACE;
        $sql .= Sql:: AND;
        $sql .= Sql::SQL_SPACE;
        $sql .= Sql::SQL_QUESTION_MARK;

        $this->sql = new Sql($sql, [$from, $to]);

        $this->operator = Sql::BETWEEN;

        return $this;
    }

    /**
     * @return Sql
     */
    public function build(): Sql
    {
        return $this->sql;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function exists(Sql $subQuery): Logical
    {
        $this->sql = $this->operatorWithSubQuery(Sql::EXISTS, $subQuery);

        $this->operator = Sql::EXISTS;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param array $values
     * @return Logical
     */
    public function in(array $values = []): Logical
    {
        $sql = Sql::IN . Sql::SQL_SPACE;
        $sql .= implode(Sql::SQL_SPACE, [
            Sql::SQL_BRACKET_OPEN,
            implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)),
            Sql::SQL_BRACKET_CLOSE
        ]);

        $this->sql = new Sql($sql, $values);

        $this->operator = Sql::IN;

        return $this;
    }

    /**
     * @param $value
     * @param bool $wildcardStart
     * @param bool $wildcardEnd
     * @return Logical
     */
    public function like($value, $wildcardStart = false, $wildcardEnd = false): Logical
    {

    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Logical
     */
    public function not($column, ?Comparison $comparison, ?Logical $logical): Logical
    {
        $this->sql = $this->operatorWithColumn(Sql::NOT, $column, $comparison, $logical);

        $this->operator = Sql::NOT;

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
        $this->sql = $this->operatorWithColumn(Sql:: OR, $column, $comparison, $logical);

        $this->operator = Sql:: OR;

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return Logical
     */
    public function some(Sql $subQuery): Logical
    {
        $this->sql = $this->operatorWithSubQuery(Sql::SOME, $subQuery);

        $this->operator = Sql::SOME;

        return $this;
    }

    /**
     * @param string $operator
     * @param Sql $subQuery
     * @return Sql
     */
    private function operatorWithSubQuery(string $operator, Sql $subQuery): Sql
    {
        $sql = $operator . Sql::SQL_SPACE;
        $sql .= $this->setNested(true)->nested(true);
        $sql .= $subQuery->sql() . Sql::SQL_SPACE;
        $sql .= $this->setNested(true)->nested(false);

        return new Sql($sql, $subQuery->parameters());
    }

    /**
     * @param string $operator
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Sql
     */
    private function operatorWithColumn(string $operator, $column, ?Comparison $comparison, ?Logical $logical): Sql
    {
        $value = [];
        if (!empty($comparison)) {
            $value = array_merge($value, $comparison->build()->parameters());
        }

        if (!empty($logical)) {
            $value = array_merge($value, $logical->build()->parameters());
        }

        $sql = $operator . Sql::SQL_SPACE;
        $sql .= $this->nested(true);
        $sql .= is_string($column) ? $column . Sql::SQL_SPACE : "";
        $sql .= ($comparison instanceof Comparison) ? $comparison->build()->sql() : "";
        $sql .= ($logical instanceof Logical) ? $logical->build()->sql() : "";

        return new Sql($sql, $value);
    }
}
