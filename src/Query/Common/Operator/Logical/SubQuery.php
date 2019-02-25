<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Logical;


use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class SubQuery
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
class SubQuery implements OperatorInterface
{
    /**
     * @var
     */
    private $sql;

    /**
     * @var string
     */
    private $operator = '';

    /**
     * @var bool
     */
    private $nested = false;

    /**
     * @var bool
     */
    private $trailingSpace = true;

    /**
     * Field constructor.
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
        if ($this->getNested()) {
            $this->setNested(false);
        }

        $this->trailingSpace = true;

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
     * @return bool
     */
    public function getNested()
    {
        return $this->nested;
    }

    /**
     * @param bool $nested
     * @return $this
     */
    public function setNested(Bool $nested = false)
    {
        $this->nested = $nested;

        return $this;
    }

    /**
     * @return $this
     */
    public function omitTrailingSpace()
    {
        $this->trailingSpace = false;

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function all(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::ALL;
        $this->sql
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN, [], $this->trailingSpace)
            ->append($string, $parameters, $this->trailingSpace)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE, [], $this->trailingSpace);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function any(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::ANY;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN, [], $this->trailingSpace)
            ->append($string, $parameters, $this->trailingSpace)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE, [], $this->trailingSpace);


        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function exists(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::EXISTS;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN, [], $this->trailingSpace)
            ->append($string, $parameters, $this->trailingSpace)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE, [], $this->trailingSpace);

        return $this;
    }

    /**
     * @param Sql $sql
     * @param bool $wrapBrackets
     * @return SubQuery
     */
    public function some(Sql $sql, $wrapBrackets = true): SubQuery
    {
        $string = $sql->string();
        $parameters = $sql->parameters();

        $this->operator = Sql::SOME;
        $this->sql
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN, [], $this->trailingSpace)
            ->append($string, $parameters, $this->trailingSpace)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE, [], $this->trailingSpace);

        return $this;
    }
}
