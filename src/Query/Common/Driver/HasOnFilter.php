<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Trait HasOnFilter
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasOnFilter
{
    /**
     * @var OnFilterInterface
     */
    private $onFilter;

    /**
     * @return OnFilterInterface
     */
    public function onFilter(): OnFilterInterface
    {
        switch($this->driverName()){
            default:
                return $this->mysqlOnFilter();
        }
    }

    /**
     * @return \Redstraw\Hooch\Builder\Mysql\OnFilter
     */
    private function mysqlOnFilter(): \Redstraw\Hooch\Builder\Mysql\OnFilter
    {
        if($this->onFilter instanceof \Redstraw\Hooch\Builder\Mysql\OnFilter){
            return $this->onFilter;
        }

        return new \Redstraw\Hooch\Builder\Mysql\OnFilter($this->query(), $this->operator());
    }
}