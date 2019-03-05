<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Statement\UpdateInterface;

/**
 * Trait HasUpdate
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasUpdate
{
    /**
     * @var UpdateInterface
     */
    private $update;

    /**
     * @return UpdateInterface
     */
    public function update(): UpdateInterface
    {
        switch($this->driverName()){
            default:
                return $this->mysqlUpdate();
        }
    }

    /**
     * @return \Redstraw\Hooch\Builder\Mysql\Update
     */
    private function mysqlUpdate(): \Redstraw\Hooch\Builder\Mysql\Update
    {
        if($this->update instanceof \Redstraw\Hooch\Builder\Mysql\Update){
            return $this->update;
        }

        return new \Redstraw\Hooch\Builder\Mysql\Update($this->query(), $this->operator());
    }
}
