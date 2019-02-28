<?php

namespace Redstraw\Hooch\Query\Operator;


use Redstraw\Hooch\Query\Sql;

/**
 * Interface SubQueryOperatorInterface
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
interface SubQueryOperatorInterface extends OperatorInterface
{
    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function all(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function any(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function exists(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function some(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function eq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function notEq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function gt(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function gtEq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function lt(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;

    /**
     * @param Sql $subQuery
     * @param bool $wrapBrackets
     * @return SubQueryOperatorInterface
     */
    public function ltEq(Sql $subQuery, bool $wrapBrackets = true): SubQueryOperatorInterface;
}
