<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasSelect
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasSelect
{
    /**
     * @var SelectInterface
     */
    private $select;

    /**
     * @return SelectInterface
     */
    public function select(): SelectInterface
    {
        switch($this->driverName()){
            default:
                return $this->mysqlSelect();
        }
    }

    /**
     * @return \Redstraw\Hooch\Builder\Mysql\Select
     */
    private function mysqlSelect(): \Redstraw\Hooch\Builder\Mysql\Select
    {
        if($this->select instanceof \Redstraw\Hooch\Builder\Mysql\Select){
            return $this->select;
        }

        return new \Redstraw\Hooch\Builder\Mysql\Select($this->query(), $this->operator());
    }
}
