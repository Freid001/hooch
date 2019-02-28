<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query;


use Redstraw\Hooch\Query\Operator\FieldOperatorInterface;
use Redstraw\Hooch\Query\Operator\ParamOperatorInterface;
use Redstraw\Hooch\Query\Operator\SubQueryOperatorInterface;

/**
 * Class Operator
 * @package Redstraw\Hooch\Query\Common\Operator
 */
class Operator
{
    /**
     * @var ParamOperatorInterface
     */
    private $paramOperator;

    /**
     * @var FieldOperatorInterface
     */
    private $fieldOperator;

    /**
     * @var SubQueryOperatorInterface
     */
    private $subQueryOperator;

    /**
     * Operator constructor.
     * @param ParamOperatorInterface $paramOperator
     * @param FieldOperatorInterface $fieldOperator
     * @param SubQueryOperatorInterface $subQueryOperator
     */
    public function __construct(
        ParamOperatorInterface $paramOperator,
        FieldOperatorInterface $fieldOperator,
        SubQueryOperatorInterface $subQueryOperator
    )
    {
        $this->paramOperator = $paramOperator;
        $this->fieldOperator = $fieldOperator;
        $this->subQueryOperator = $subQueryOperator;
    }

    /**
     * @return ParamOperatorInterface
     */
    public function param(): ParamOperatorInterface
    {
        return $this->paramOperator;
    }

    /**
     * @return SubQueryOperatorInterface
     */
    public function sql(): SubQueryOperatorInterface
    {
        return $this->subQueryOperator;
    }

    /**
     * @return FieldOperatorInterface
     */
    public function field(): FieldOperatorInterface
    {
        return $this->fieldOperator;
    }
}
