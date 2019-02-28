<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Driver\DriverInterface;
use Redstraw\Hooch\Query\Statement\UpdateInterface;

/**
 * Trait HasUpdate
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasUpdate
{
    /**
     * @var UpdateInterface|null
     */
    private $update = null;

    /**
     * @return UpdateInterface|null
     */
    public function update(): ?UpdateInterface
    {
        if($this instanceof DriverInterface){
            switch($this->driverName()){
                case DriverInterface::DRIVER_MYSQL:
                    $this->update = new \Redstraw\Hooch\Builder\Mysql\Update($this->query(), $this->operator());
                    break;

                case DriverInterface::DRIVER_PGSQL:
                    break;

                case DriverInterface::DRIVER_SQLITE:
                    break;

                default:
                    $this->logger()->error(sprintf("Driver: %u not supported.", $this->driverName()));
                    $this->update = null;
            }
        }

        return $this->update;
    }
}
