<?php

namespace QueryMule\Query\Repository\Table;

use QueryMule\Builder\Sql\Generic\Filter;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
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

        $filter = $this->driver->getStatement('filter');
        if($filter instanceof FilterInterface) {
            $this->filter = $filter;
        }else {
            $this->filter = $this->driver->filter();
        }

        $select = $this->driver->getStatement('select');
        if($select instanceof SelectInterface){
            $this->select = $select;
        }else {
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
    public function select(array $cols = [Sql::SQL_STAR], $alias = null) : SelectInterface
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
