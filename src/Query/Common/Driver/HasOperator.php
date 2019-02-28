<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Sql;

/**
 * Trait HasOperator
 * @package Redstraw\Hooch\Query\Common\Driver
 */
trait HasOperator
{
    /**
     * @var null
     */
    private $operator = null;

    /**
     * @return Operator|null
     */
    public function operator(): ?Operator
    {
        if($this instanceof DriverInterface){
            switch($this->driverName()){
                case DriverInterface::DRIVER_MYSQL:
                    $this->operator = new Operator(
                        new \Redstraw\Hooch\Builder\Mysql\Operator\Param(new Sql(), $this->query()->accent()),
                        new \Redstraw\Hooch\Builder\Mysql\Operator\Field(new Sql(), $this->query()->accent()),
                        new \Redstraw\Hooch\Builder\Mysql\Operator\SubQuery(new Sql(), $this->query()->accent())
                    );
                    break;

                case DriverInterface::DRIVER_PGSQL:
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    break;

                default:
                    $this->logger()->error(sprintf("Driver: %u not supported.", $this->driverName()));
                    $this->operator = null;
            }
        }

        return $this->operator;
    }
}
