<?php

namespace Redstraw\Hooch\Query\Operator;


/**
 * Interface ParamOperatorInterface
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
interface ParamOperatorInterface extends OperatorInterface
{
    /**
     * @param $from
     * @param $to
     * @return ParamOperatorInterface
     */
    public function between($from, $to): ParamOperatorInterface;

    /**
     * @param array $values
     * @return ParamOperatorInterface
     */
    public function in(array $values = []): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function like($value): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function eq($value): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function notEq($value): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function gt($value): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function gtEq($value): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function lt($value): ParamOperatorInterface;

    /**
     * @param $value
     * @return ParamOperatorInterface
     */
    public function ltEq($value): ParamOperatorInterface;
}
