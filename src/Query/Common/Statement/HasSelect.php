<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Common\Statement;

use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Exception\DriverException;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasSelect
 * @package Redstraw\Hooch\Builder\Common\Statement
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
                    $this->select = new \Redstraw\Hooch\Builder\Sql\Mysql\Select($this->query());
                    break;

                case DriverInterface::DRIVER_PGSQL:
                    //$this->select = new \Redstraw\Hooch\Builder\Sql\Pgsql\Select($cols, $repository);
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    //$this->select = new \Redstraw\Hooch\Builder\Sql\Sqlite\Select($cols, $repository);
                    break;

                default:
                    throw new DriverException(sprintf("Driver: %u not currently supported!", $this->driver()));
            }
        }

        return $this->select;
    }
}
