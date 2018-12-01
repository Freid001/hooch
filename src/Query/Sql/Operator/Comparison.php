<?php


namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Class Comparison
 * @package QueryMule\Query\Sql\Operator
 */
class Comparison implements QueryBuilderInterface
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
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []): Sql
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
     * @param $operator
     * @param Sql $sql
     * @return Sql
     */
    private function operatorWithSql($operator, Sql $sql)
    {
        $sqlObj = new Sql($operator);
        $sqlObj->append(Sql::SQL_BRACKET_OPEN)
               ->append($sql,[],false)
               ->append(Sql::SQL_BRACKET_CLOSE);

        return $sqlObj;
    }

    /**
     * @param $operator
     * @param $value
     * @return Sql
     */
    private function operatorWithValue($operator, $value)
    {
        $sql = new Sql($operator,[$value],false);
        $sql->append(Sql::SQL_QUESTION_MARK,[],false);

        return $sql;
    }
}