<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Mysql\Operator;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Operator\ParamOperatorInterface;
use Redstraw\Hooch\Query\Sql;

/**
 * Class Param
 * @package Redstraw\Hooch\Builder\Sql\Mysql\Operator\Field
 */
class Param implements ParamOperatorInterface
{
    /**
     * @var Accent
     */
    private $accent;

    /**
     * @var Sql
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
     * Field constructor.
     * @param Sql $sql
     * @param Accent $accent
     */
    public function __construct(Sql $sql, Accent $accent)
    {
        $this->sql = $sql;
        $this->accent = $accent;
    }

    /**
     * @return Accent
     */
    public function accent(): Accent
    {
        return $this->accent;
    }

    /**
     * @return Sql
     */
    public function sql(): Sql
    {
        return $this->sql;
    }

    /**
     * @return string
     */
    public function operator(): string
    {
        return $this->operator;
    }

    /**
     * @param bool $nested
     */
    public function setNested(bool $nested): void
    {
        $this->nested = $nested;
    }

    /**
     * @return bool
     */
    public function isNested(): bool
    {
        return $this->nested;
    }

    /**
     * @param $from
     * @param $to
     * @return ParamOperatorInterface
     */
    public function between($from, $to): ParamOperatorInterface
    {
        $this->operator = Sql::BETWEEN;
        $this->sql()
            ->reset()
            ->append($this->operator())
            ->ifThenAppend($this->isNested(), Sql::SQL_BRACKET_OPEN)
            ->append(Sql::SQL_QUESTION_MARK, [$from])
            ->append(Sql:: AND)
            ->append(Sql::SQL_QUESTION_MARK, [$to], false);

        $this->setNested(false);

        return $this;
    }

    /**
     * @param array $values
     * @return ParamOperatorInterface
     */
    public function in(array $values = []): ParamOperatorInterface
    {
        $this->operator = Sql::IN;
        $this->sql()
            ->reset()
            ->append($this->operator())
            ->ifThenAppend($this->isNested(), Sql::SQL_BRACKET_OPEN)
            ->append(implode(Sql::SQL_SPACE, [
                Sql::SQL_BRACKET_OPEN,
                implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)),
                Sql::SQL_BRACKET_CLOSE
            ]), $values, false);

        $this->setNested(false);

        return $this;
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function like($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_LIKE;
        $this->sql()
            ->reset()
            ->append($this->operator())
            ->ifThenAppend($this->isNested(), Sql::SQL_BRACKET_OPEN)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        $this->setNested(false);

        return $this;
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function eq($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_EQUAL;

        return $this->comparison($value);
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function notEq($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;

        return $this->comparison($value);
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function gt($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_GREATER_THAN;

        return $this->comparison($value);
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function gtEq($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_GREATER_THAN.Sql::SQL_EQUAL;

        return $this->comparison($value);
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function lt($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN;

        return $this->comparison($value);
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function ltEq($value): ParamOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN.Sql::SQL_EQUAL;

        return $this->comparison($value);
    }

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    private function comparison($value): ParamOperatorInterface
    {
        $this->sql()
            ->reset()
            ->append($this->operator(),[],false)
            ->append(Sql::SQL_QUESTION_MARK, [$value], false);

        return $this;
    }
}
