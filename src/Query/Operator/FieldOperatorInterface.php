<?php

namespace Redstraw\Hooch\Query\Operator;


use Redstraw\Hooch\Query\Field\FieldInterface;

/**
 * Interface FieldOperatorInterface
 * @package Redstraw\Hooch\Query
 */
interface FieldOperatorInterface extends OperatorInterface
{
    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FieldOperatorInterface
     */
    public function and(?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface;

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FieldOperatorInterface
     */
    public function or(?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface;

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FieldOperatorInterface
     */
    public function not(?FieldInterface $field, OperatorInterface $operator): FieldOperatorInterface;

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function eq(FieldInterface $field): FieldOperatorInterface;

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function notEq(FieldInterface $field): FieldOperatorInterface;

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function gt(FieldInterface $field): FieldOperatorInterface;

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function gtEq(FieldInterface $field): FieldOperatorInterface;

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function lt(FieldInterface $field): FieldOperatorInterface;

    /**
     * @param FieldInterface $field
     * @return FieldOperatorInterface
     */
    public function ltEq(FieldInterface $field): FieldOperatorInterface;
}
