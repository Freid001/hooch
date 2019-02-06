<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Common\Statement;


use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Exception\DriverException;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasOnFilter
 * @package Redstraw\Hooch\Builder\Common\Statement
 */
trait HasOnFilter
{
    /**
     * @return OnFilterInterface|null
     * @throws DriverException
     */
    public function onFilter(): ?OnFilterInterface
    {
        if($this instanceof DriverInterface){
            switch($this->driver()){
                case DriverInterface::DRIVER_MYSQL:
                    return new \Redstraw\Hooch\Builder\Sql\Mysql\OnFilter($this->query());

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