<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Query;
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

    public function query(): Query
    {
        return new Query(new Sql(), new Logical(), new Accent());
    }

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
        $this->operator(Sql::SQL_EQUAL, $value);

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
        $this->operator(Sql::SQL_GREATER_THAN, $value);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function greaterThanEqualTo($value): Comparison
    {
        $this->operator(Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL, $value);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function lessThan($value): Comparison
    {
        $this->operator(Sql::SQL_LESS_THAN, $value);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function lessThanEqualTo($value): Comparison
    {
        $this->operator(Sql::SQL_LESS_THAN . Sql::SQL_EQUAL, $value);

        return $this;
    }

    /**
     * @param $value
     * @return Comparison
     */
    public function notEqualTo($value): Comparison
    {
        $this->operator(Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN, $value);

        return $this;
    }

    /**
     * @param string $operator
     * @param $value
     */
    private function operator(string $operator, $value): void
    {
        $this->operator = $operator;
        switch ((gettype($value) == 'object') ? get_class($value) : null)
        {
            case Sql::class:
                /** @var Sql $value */
                $this->sql = $this->operatorWithSql($this->operator, $value);
                break;

            case Logical::class:
                /** @var Logical $value */
                $this->sql = $this->operatorWithSql($this->operator, $value->build(), false);
                break;

            default:
                /** @var String $value */
                $this->sql = $this->operatorWithValue($this->operator, $value);
        }
    }

    /**
     * @param string $operator
     * @param Sql $sql
     * @param bool $subQuery
     * @return Sql
     */
    private function operatorWithSql(string $operator, Sql $sql, bool $subQuery = true): Sql
    {
        $sqlObj = new Sql($operator);
        $sqlObj->ifThenAppend($subQuery,Sql::SQL_BRACKET_OPEN)
               ->append($sql,[],false)
               ->ifThenAppend($subQuery,Sql::SQL_BRACKET_CLOSE);

        return $sqlObj;
    }

    /**
     * @param string $operator
     * @param $value
     * @return Sql
     */
    private function operatorWithValue(string $operator, $value): Sql
    {
        $sql = new Sql($operator,[],false);
        $sql->append(Sql::SQL_QUESTION_MARK,[$value],false);

        return $sql;
    }
}