<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Statement;


use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Exception\DriverException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasFilter
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasFilter
{
    /**
     * @return DriverInterface|null
     * @throws DriverException
     */
    public function filter(): ?FilterInterface
    {
        if($this instanceof DriverInterface){
            switch($this->driver()){
                case DriverInterface::DRIVER_MYSQL:
                    return new \Redstraw\Hooch\Builder\Sql\Mysql\Filter($this->query());

                case DriverInterface::DRIVER_PGSQL:
                    //$this->filter = new \Redstraw\Hooch\Builder\Sql\Pgsql\Filter();
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    //$this->filter = new \Redstraw\Hooch\Builder\Sql\Sqlite\Filter();
                    break;

                default:
                    throw new DriverException(sprintf("Driver: %u not currently supported!", $this->driver()));
            }
        }

        return null;
    }
}