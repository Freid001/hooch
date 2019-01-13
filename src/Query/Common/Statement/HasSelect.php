<?php

declare(strict_types=1);

namespace QueryMule\Builder\Common\Statement;

use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Exception\DriverException;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasSelect
 * @package QueryMule\Builder\Common\Statement
 */
trait HasSelect
{
    /**
     * @var SelectInterface|null
     */
    private $select = null;

    /**
     * @return SelectInterface|null
     * @throws DriverException
     */
    public function select(): ?SelectInterface
    {
        if($this instanceof DriverInterface){
            switch($this->driver()){
                case DriverInterface::DRIVER_MYSQL:
                    $this->select = new \QueryMule\Builder\Sql\MySql\Select($this->query());
                    break;

                case DriverInterface::DRIVER_PGSQL:
                    //$this->select = new \QueryMule\Builder\Sql\Pgsql\Select($cols, $repository);
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    //$this->select = new \QueryMule\Builder\Sql\Sqlite\Select($cols, $repository);
                    break;

                default:
                    throw new DriverException(sprintf("Driver: %u not currently supported!", $this->driver()));
            }
        }

        return $this->select;
    }
}