<?php

namespace QueryMule\Builder\Connection\Driver;

use QueryMule\Builder\Exception\DriverException;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class PdoDriver
 * @package QueryMule\Builder\Connection\Driver
 */
class PdoDriver implements DriverInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $driver;

    /**
     * PdoAdapter constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }

    /**
     * @return FilterInterface
     * @throws DriverException
     */
    public function filter() : FilterInterface
    {
        $filter = null;
        switch($this->driver){
            case self::DRIVER_MYSQL:
                $filter = new \QueryMule\Builder\Sql\Mysql\Filter();
                break;

            case self::DRIVER_PGSQL:
                $filter = new \QueryMule\Builder\Sql\Pgsql\Filter();
                break;

            case self::DRIVER_SQLITE:
                $filter = new \QueryMule\Builder\Sql\Sqlite\Filter();
                break;

            default:
                throw new DriverException('Driver: '.$this->driver.' not currently supported');
        }

        return $filter;
    }

    /**
     * @param array $cols
     * @param RepositoryInterface|null $table
     * @return SelectInterface
     * @throws DriverException
     */
    public function select(array $cols = [],RepositoryInterface $table = null) : SelectInterface
    {
        $select = null;
        switch($this->driver){
            case self::DRIVER_MYSQL:
                $select = new \QueryMule\Builder\Sql\Mysql\Select($cols, $table);
                break;

            case self::DRIVER_PGSQL:
                $select = new \QueryMule\Builder\Sql\Pgsql\Select($cols, $table);
                break;

            case self::DRIVER_SQLITE:
                $select = new \QueryMule\Builder\Sql\Sqlite\Select($cols, $table);
                break;

            default:
                throw new DriverException('Driver: '.$this->driver.' not currently supported');
        }

        return $select;
    }

    /**
     * @param Sql $sql
     * @return array
     */
    public function fetch(Sql $sql)
    {
        return $this->execute($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param Sql $sql
     * @return array
     */
    public function fetchAll(Sql $sql)
    {
        return $this->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param Sql $sql
     * @return \PDOStatement
     * @throws DriverException
     */
    private function execute(Sql $sql)
    {
        $query = $this->pdo->prepare($sql->sql());

        if (!$query || !$query->execute($sql->parameters())) {
            //throw new DriverException('PDO error code: ' . $this->pdo->errorCode());
            throw new DriverException($this->pdo->errorInfo());
        }

        return $query;
    }
}