<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Driver;


use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasFetchAll
 * @package QueryMule\Query\Common\Statement
 */
trait HasFetchAll
{
    /**
     * @param Sql $sql
     * @return array|mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function fetchAll(Sql $sql)
    {
        if($this instanceof DriverInterface){
            return $this->execute($sql, DriverInterface::FETCH_ALL);
        }

        return null;
    }
}