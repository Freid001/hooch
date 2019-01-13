<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Statement;


use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Exception\DriverException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasFilter
 * @package QueryMule\Query\Common\Statement
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
                    return new \QueryMule\Builder\Sql\Mysql\Filter($this->query());

                case DriverInterface::DRIVER_PGSQL:
                    //$this->filter = new \QueryMule\Builder\Sql\Pgsql\Filter();
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    //$this->filter = new \QueryMule\Builder\Sql\Sqlite\Filter();
                    break;

                default:
                    throw new DriverException(sprintf("Driver: %u not currently supported!", $this->driver()));
            }
        }

        return null;
    }
}