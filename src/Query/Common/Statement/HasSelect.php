<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Statement;


use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasSelect
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasSelect
{
    /**
     * @var SelectInterface|null
     */
    private $select = null;

    /**
     * @return SelectInterface|null
     */
    public function select(): ?SelectInterface
    {
        if($this instanceof DriverInterface){
            switch($this->driverName()){
                case DriverInterface::DRIVER_MYSQL:
                    $this->select = new \Redstraw\Hooch\Builder\Sql\Mysql\Select($this->query(), $this->operator());
                    break;

                case DriverInterface::DRIVER_PGSQL:
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    break;

                default:
                    $this->logger()->error(sprintf("Driver: %u not supported.", $this->driverName()));
                    $this->select = null;
            }
        }

        return $this->select;
    }
}
