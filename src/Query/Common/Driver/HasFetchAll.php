<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Driver;


use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Trait HasFetchAll
 * @package Redstraw\Hooch\Query\Common\Statement
 */
trait HasFetchAll
{
    /**
     * @param Sql $sql
     * @return array|mixed|null
     */
    public function fetchAll(Sql $sql)
    {
        if($this instanceof DriverInterface){
            return $this->execute($sql, DriverInterface::FETCH_ALL);
        }

        return null;
    }
}
