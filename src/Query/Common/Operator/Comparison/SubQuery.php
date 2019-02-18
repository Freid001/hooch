<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Comparison;


use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class SubQuery
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
class SubQuery implements OperatorInterface
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
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function equalTo(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($string, $parameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }

    /**
     * @param Sql $sql
     * @return SubQuery
     */
    public function greaterThan(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SQL_GREATER_THAN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($string, $parameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function greaterThanEqualTo(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SQL_GREATER_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($string, $parameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function lessThan(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SQL_LESS_THAN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($string, $parameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function lessThanEqualTo(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($string, $parameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function notEqualTo(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($string, $parameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }
}
