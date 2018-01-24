<?php

namespace QueryMule\Query\Repository\Table;

use QueryMule\Query\Connection\Driver\DriverInterface;
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
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var SelectInterface
     */
    protected $select;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * AbstractTable constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;

        $this->filter = $this->driver->getStatement('filter');
        if(empty($this->filter)) {
            $this->filter = $this->driver->filter();
        }

        $this->select = $this->driver->getStatement('select');
        if(empty($this->select)) {
            $this->select = $this->driver->select();
        }
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
    public function filter() : FilterInterface
    {
        return $this->filter;
    }
}