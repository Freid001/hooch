<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Trait HasFetch
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasFetch
{
    /**
     * @param Sql $sql
     * @return mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function fetch(Sql $sql)
    {
        if($this instanceof DriverInterface){
            return $this->execute($sql, DriverInterface::FETCH);
        }

        return null;
    }
}
