<?php


namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\Sql\Sql;

/**
 * Class Comparison
 * @package QueryMule\Query\Sql\Operator
 */
class Comparison
{
    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var string
     */
    private $operator;

    /**
     * @return Sql
     */
    public function build(): Sql
    {
        return $this->sql;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function equalTo($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = $this->operatorWithSql(Sql::SQL_EQUAL, $value);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_EQUAL, $value);
        }

        $this->operator = Sql::SQL_EQUAL;

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
     * @param $value
     * @return Comparison
     */
    public function greaterThan($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = $this->operatorWithSql(Sql::SQL_GREATER_THAN, $value);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_GREATER_THAN, $value);
        }

        $this->operator = Sql::SQL_GREATER_THAN;

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function greaterThanEqualTo($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = $this->operatorWithSql(Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL, $value);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL, $value);
        }

        $this->operator = Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL;

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function lessThan($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN, $value);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_LESS_THAN, $value);
        }

        $this->operator = Sql::SQL_LESS_THAN;

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function lessThanEqualTo($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_EQUAL, $value);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_LESS_THAN . Sql::SQL_EQUAL, $value);
        }

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function notEqualTo($value): Comparison
    {
        if ($value instanceof Sql) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value);
        }

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;

        return $this;
    }

    /**
     * @param $comparison
     * @param Sql $sql
     * @return Sql
     */
    private function operatorWithSql($comparison, Sql $sql)
    {
        return new Sql(
            $comparison .
            Sql::SQL_SPACE .
            Sql::SQL_BRACKET_OPEN .
            Sql::SQL_SPACE .
            $sql->sql() .
            Sql::SQL_SPACE .
            Sql::SQL_BRACKET_CLOSE .
            Sql::SQL_SPACE,
            $sql->parameters()
        );
    }

    /**
     * @param $comparison
     * @param $value
     * @return Sql
     */
    private function operatorWithValue($comparison, $value)
    {
        return new Sql(
            $comparison .
            Sql::SQL_QUESTION_MARK,
            [$value]
        );
    }
}