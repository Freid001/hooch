<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Class Comparison
 * @package QueryMule\Query\Sql\Operator
 */
class Comparison implements QueryBuilderInterface, OperatorInterface
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
        switch ((gettype($value) == 'object') ? get_class($value) : null){
            case Sql::class:
                /** @var Sql $value */
                $this->sql = $this->operatorWithSql(Sql::SQL_EQUAL, $value);
                break;

            case Logical::class:
                /** @var Logical $value */
                $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value->build(), false);
                break;

            default:
                /** @var String $value */
                $this->sql = $this->operatorWithValue(Sql::SQL_EQUAL, $value);
        }

        $this->operator = Sql::SQL_EQUAL;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator(): String
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
        } else if ($value instanceof Logical) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value->build(), false);
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
        } else if ($value instanceof Logical) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value->build(), false);
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
        } else if ($value instanceof Logical) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value->build(), false);
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
        } else if ($value instanceof Logical) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value->build(), false);
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
        } else if ($value instanceof Logical) {
            $this->sql = $this->operatorWithSql(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value->build(), false);
        } else {
            $this->sql = $this->operatorWithValue(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value);
        }

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;

        return $this;
    }

    /**
     * @param $operator
     * @param Sql $sql
     * @param bool $subQuery
     * @return Sql
     */
    private function operatorWithSql($operator, Sql $sql, bool $subQuery = true): Sql
    {
        $sqlObj = new Sql($operator);
        $sqlObj->appendIf($subQuery,Sql::SQL_BRACKET_OPEN)
               ->append($sql,[],false)
               ->appendIf($subQuery,Sql::SQL_BRACKET_CLOSE);

        return $sqlObj;
    }

    /**
     * @param $operator
     * @param $value
     * @return Sql
     */
    private function operatorWithValue($operator, $value): Sql
    {
        $sql = new Sql($operator,[],false);
        $sql->append(Sql::SQL_QUESTION_MARK,[$value],false);

        return $sql;
    }
}