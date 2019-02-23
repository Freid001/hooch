<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Statement;


use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasFilter
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasFilter
{
    /**
     * @return FilterInterface|null
     */
    public function filter(): ?FilterInterface
    {
        if($this instanceof DriverInterface){
            switch($this->driverName()){
                case DriverInterface::DRIVER_MYSQL:
                    return new \Redstraw\Hooch\Builder\Sql\Mysql\Filter($this->query(), $this->operator());

                case DriverInterface::DRIVER_PGSQL:
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    break;

                default:
                    $this->logger()->error(sprintf("Driver: %u not supported.", $this->driverName()));
            }
        }

        return null;
    }
}
