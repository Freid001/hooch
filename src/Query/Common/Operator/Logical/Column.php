<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Logical;


use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class Column
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
class Column implements OperatorInterface
{
    /**
     * @var
     */
    private $sql;

    /**
     * @var Accent
     */
    private $accent;

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
     * Column constructor.
     * @param Sql $sql
     * @param Accent $accent
     */
    public function __construct(Sql $sql, Accent $accent)
    {
        $this->sql = $sql;
        $this->accent = $accent;
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
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return Column
     */
    public function and (?string $column, OperatorInterface $operator): Column
    {
        $operatorString = $operator->build()->string();
        $operatorParameters = $operator->build()->parameters();

        $this->operator = Sql:: AND;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend(!empty($column), $this->accent->append($column,'.'))
            ->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, $this->trailingSpace);

        return $this;
    }

    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return Column
     */
    public function not(?string $column, OperatorInterface $operator): Column
    {
        $operatorString = $operator->build()->string();
        $operatorParameters = $operator->build()->parameters();

        $this->operator = Sql::NOT;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend(!empty($column),$this->accent->append($column,'.'))
            ->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, $this->trailingSpace);

        return $this;
    }

    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return Column
     */
    public function or (?string $column, OperatorInterface $operator): Column
    {
        $operatorString = $operator->build()->string();
        $operatorParameters = $operator->build()->parameters();

        $this->operator = Sql::OR;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->ifThenAppend($this->nested, Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend(!empty($column), $this->accent->append($column,'.'))
            ->ifThenAppend(!empty($operatorString), $operatorString, $operatorParameters, $this->trailingSpace);

        return $this;
    }
}
