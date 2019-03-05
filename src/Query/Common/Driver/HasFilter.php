<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasFilter
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasFilter
{
    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @return FilterInterface
     */
    public function filter(): FilterInterface
    {
        switch($this->driverName()){
            default:
                return $this->mysqlFilter();
        }
    }

    /**
     * @return \Redstraw\Hooch\Builder\Mysql\Filter
     */
    private function mysqlFilter(): \Redstraw\Hooch\Builder\Mysql\Filter
    {
        if($this->filter instanceof \Redstraw\Hooch\Builder\Mysql\Filter){
            return $this->filter;
        }

        return new \Redstraw\Hooch\Builder\Mysql\Filter($this->query(), $this->operator());
    }
}
