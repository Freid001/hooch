<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Comparison;


use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class ParamComparison
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
class Param implements OperatorInterface
{
    /**
     * @var Sql
     */
    private $sql;

    /**
     * @var string
     */
    private $operator = '';

    /**
     * Param constructor.
     * @param Sql $sql
     */
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return Sql
     */
    public function build(): Sql
    {
        return $this->sql;
    }

    /**
     * @return String
     */
    public function getOperator(): String
    {
        return $this->operator;
    }

    /**
     * @param $value
     * @return Param
     */
    public function equalTo($value): Param
    {
        $this->operator = Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator, [], false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }

    /**
     * @param $value
     * @return Param
     */
    public function greaterThan($value): Param
    {
        $this->operator = Sql::SQL_GREATER_THAN;
        $this->sql
            ->reset()
            ->append($this->operator, [], false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }

    /**
     * @param $value
     * @return Param
     */
    public function greaterThanEqualTo($value): Param
    {
        $this->operator = Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator, [], false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }

    /**
     * @param $value
     * @return Param
     */
    public function lessThan($value): Param
    {
        $this->operator = Sql::SQL_LESS_THAN;
        $this->sql
            ->reset()
            ->append($this->operator, [], false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }

    /**
     * @param $value
     * @return Param
     */
    public function lessThanEqualTo($value): Param
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator, [], false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }

    /**
     * @param $value
     * @return Param
     */
    public function notEqualTo($value): Param
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;
        $this->sql
            ->reset()
            ->append($this->operator, [], false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }
}
