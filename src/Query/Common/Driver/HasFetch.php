<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Driver;


use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasFetch
 * @package QueryMule\Query\Common\Statement
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
