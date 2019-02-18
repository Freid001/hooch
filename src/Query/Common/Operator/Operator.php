<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator;


/**
 * Class Operator
 * @package Redstraw\Hooch\Query\Common\Operator
 */
class Operator
{
    /**
     * @var Comparison
     */
    private $comparison;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * Operator constructor.
     * @param Comparison $comparison
     * @param Logical $logical
     */
    public function __construct(Comparison $comparison, Logical $logical)
    {
        $this->comparison = $comparison;
        $this->logical = $logical;
    }

    /**
     * @return Comparison
     */
    public function comparison(): Comparison
    {
        return $this->comparison;
    }

    /**
     * @return Logical
     */
    public function logical(): Logical
    {
        return $this->logical;
    }
}
