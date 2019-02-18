<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common;


use Redstraw\Hooch\Query\Common\Operator\Operator;

/**
 * Trait HasQuery
 * @package Redstraw\Hooch\Query\Common
 */
trait HasOperator
{
    /**
     * @var Operator
     */
    private $operator;

    /**
     * @return Operator
     */
    public function operator(): Operator
    {
        return $this->operator;
    }
}
