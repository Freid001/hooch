<?php

namespace QueryMule\Query\Repository\Table;

use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class AbstractTable
 * @package QueryMule\Query\Repository\Table
 */
abstract class AbstractTable implements RepositoryInterface
{
    /**
     * @var \QueryMule\Query\Sql\Statement\SelectInterface
     */
    protected $select;

    /**
     * @var \QueryMule\Query\Sql\Statement\FilterInterface
     */
    protected $filter;

    /**
     * AbstractTable constructor.
     * @param \QueryMule\Query\Connection\Driver\DriverInterface $driver
     */
    public function __construct(\QueryMule\Query\Connection\Driver\DriverInterface $driver)
    {
        $this->select = $driver->select();
        $this->filter = $driver->filter();
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param array $cols
     * @return \QueryMule\Query\Sql\Statement\SelectInterface
     */
    public function select(array $cols = [SelectInterface::SQL_STAR], $alias = null) : SelectInterface
    {
        return $this->select->cols($cols)->from($this,$alias);
    }

    /**
     * @return \QueryMule\Query\Sql\Statement\FilterInterface
     */
    public function getFilter() : FilterInterface
    {
        return $this->filter;
    }
}