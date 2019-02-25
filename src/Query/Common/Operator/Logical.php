<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator;


use Redstraw\Hooch\Query\Common\Operator\Logical\Field;
use Redstraw\Hooch\Query\Common\Operator\Logical\Param;
use Redstraw\Hooch\Query\Common\Operator\Logical\SubQuery;

/**
 * Class Logical
 * @package Redstraw\Hooch\Query\Sql\Operator
 */
class Logical
{
    /**
     * @var Param
     */
    private $param;

    /**
     * @var SubQuery
     */
    private $subQuery;

    /**
     * @var Field
     */
    private $field;

    /**
     * Comparison constructor.
     * @param Param $param
     * @param SubQuery $subQuery
     * @param Field $field
     */
    public function __construct(Param $param, SubQuery $subQuery, Field $field)
    {
        $this->param = $param;
        $this->subQuery = $subQuery;
        $this->field = $field;
    }

    /**
     * @return Param
     */
    public function param(): Param
    {
        return $this->param;
    }

    /**
     * @return SubQuery
     */
    public function sql(): SubQuery
    {
        return $this->subQuery;
    }

    /**
     * @return Field
     */
    public function field(): Field
    {
        return $this->field;
    }
}