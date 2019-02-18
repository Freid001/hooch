<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Operator;


use Redstraw\Hooch\Query\Common\Operator\Comparison\Column;
use Redstraw\Hooch\Query\Common\Operator\Comparison\Param;
use Redstraw\Hooch\Query\Common\Operator\Comparison\SubQuery;

/**
 * Comparison
 */
class Comparison
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
     * @var Column
     */
    private $column;

    /**
     * Comparison constructor.
     * @param Param $param
     * @param SubQuery $subQuery
     * @param Column $column
     */
    public function __construct(Param $param, SubQuery $subQuery, Column $column)
    {
        $this->param = $param;
        $this->subQuery = $subQuery;
        $this->column = $column;
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
     * @return Column
     */
    public function column(): Column
    {
        return $this->column;
    }
}
