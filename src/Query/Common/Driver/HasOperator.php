<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Sql;

/**
 * Trait HasOperator
 * @package Redstraw\Hooch\Query\Common\Driver
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
        switch($this->driverName()){
            default:
                return $this->mysqlOperator();
        }
    }

    /**
     * @return Operator
     */
    private function mysqlOperator(): Operator
    {
        if($this->operator instanceof Operator){
            return $this->operator;
        }

        return new Operator(
            new \Redstraw\Hooch\Builder\Mysql\Operator\Param(new Sql(), $this->query()->accent()),
            new \Redstraw\Hooch\Builder\Mysql\Operator\Field(new Sql(), $this->query()->accent()),
            new \Redstraw\Hooch\Builder\Mysql\Operator\SubQuery(new Sql(), $this->query()->accent())
        );
    }
}
