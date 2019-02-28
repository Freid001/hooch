<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Mysql\Operator;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Operator\SubQueryOperatorInterface;
use Redstraw\Hooch\Query\Sql;

/**
 * Class SubQuery
 * @package Redstraw\Hooch\Builder\Sql\Mysql\Operator
 */
class SubQuery implements SubQueryOperatorInterface
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
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function all(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::ALL;

        return $this->logical($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function any(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::ANY;

        return $this->logical($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function exists(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::EXISTS;

        return $this->logical($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function some(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::SOME;

        return $this->logical($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function eq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::SQL_EQUAL;

        return $this->comparison($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function notEq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN . Sql::SQL_GREATER_THAN;

        return $this->comparison($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function gt(Sql $subQuery, bool $wrapBrackets = true ): SubQueryOperatorInterface
    {
        $this->operator = Sql::SQL_GREATER_THAN;

        return $this->comparison($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function gtEq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::SQL_GREATER_THAN.Sql::SQL_EQUAL;

        return $this->comparison($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function lt(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN;

        return $this->comparison($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function ltEq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface
    {
        $this->operator = Sql::SQL_LESS_THAN.Sql::SQL_EQUAL;

        return $this->comparison($subQuery, $wrapBrackets);
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    private function comparison(Sql $subQuery, bool $wrapBrackets): SubQueryOperatorInterface
    {
        $subQueryString = $subQuery->queryString();
        $subQueryParameters = $subQuery->parameters();

        $this->sql()
            ->reset()
            ->append($this->operator())
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN)
            ->append($subQueryString, $subQueryParameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    private function logical(Sql $subQuery, bool $wrapBrackets): SubQueryOperatorInterface
    {
        $subQueryString = $subQuery->queryString();
        $subQueryParameters = $subQuery->parameters();

        $this->sql()
            ->reset()
            ->append($this->operator())
            ->ifThenAppend($this->isNested(), Sql::SQL_BRACKET_OPEN)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_OPEN, [], false)
            ->append($subQueryString, $subQueryParameters, false)
            ->ifThenAppend($wrapBrackets, Sql::SQL_BRACKET_CLOSE, [], false);

        $this->setNested(false);

        return $this;
    }
}
