<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Logical;


use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class Param
 * @package Redstraw\Hooch\Query\Sql\Operator\Logical
 */
class Param implements OperatorInterface
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
     * @param $from
     * @param $to
     * @return Param
     */
    public function between($from, $to): Param
    {
        $this->operator = Sql::BETWEEN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->append(Sql::SQL_QUESTION_MARK, [$from])
            ->append(Sql:: AND)
            ->append(Sql::SQL_QUESTION_MARK,[$to],$this->trailingSpace);

        return $this;
    }

    /**
     * @param array $values
     * @return Param
     */
    public function in(array $values = []): Param
    {
        $this->operator = Sql::IN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->append(implode(Sql::SQL_SPACE, [
                Sql::SQL_BRACKET_OPEN,
                implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)),
                Sql::SQL_BRACKET_CLOSE
            ]), $values, $this->trailingSpace);

        return $this;
    }

    /**
     * @param $value
     * @return Param
     */
    public function like($value): Param
    {
        $this->operator = Sql::SQL_LIKE;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->append(Sql::SQL_QUESTION_MARK, [$value], $this->trailingSpace);

        return $this;
    }
}
