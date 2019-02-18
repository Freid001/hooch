<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator\Comparison;


use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Class ComparisonColumn
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
     * @param string|null $column
     * @return Column
     */
    public function equalTo(?string $column): Column
    {
        $this->operator = Sql::SQL_EQUAL;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->append($this->accent->append($column,'.'), [], false);

        return $this;
    }

    /**
     * @param string|null $column
     * @return Column
     */
    public function greaterThan(?string $column): Column
    {
        $this->operator = Sql::SQL_GREATER_THAN;
        $this->sql
            ->reset()
            ->append($this->operator)
            ->append($this->accent->append($column,'.'), [], false);

        return $this;
    }

    /**
     * @param string|null $column
     * @return Column
     */
    public function greaterThanEqualTo(?string $column): Column
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->append($this->operator)
            ->append($this->accent->append($column,'.'), [], false);

        return $this;
    }

    /**
     * @param string|null $column
     * @return Column
     */
    public function lessThan(?string $column): Column
    {
        $this->operator = Sql::SQL_LESS_THAN;
        $this->sql
            ->append($this->operator)
            ->append($this->accent->append($column,'.'), [], false);

        return $this;
    }

    /**
     * @param string|null $column
     * @return Column
     */
    public function lessThanEqualTo(?string $column): Column
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_EQUAL;
        $this->sql
            ->append($this->operator)
            ->append($this->accent->append($column,'.'), [], false);

        return $this;
    }

    /**
     * @param string|null $column
     * @return Column
     */
    public function notEqualTo(?string $column): Column
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;
        $this->sql
            ->append($this->operator)
            ->append($this->accent->append($column,'.'), [], false);

        return $this;
    }
}
