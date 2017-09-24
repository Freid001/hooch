<?php

namespace QueryMule\Builder\Adapter;

use QueryMule\Builder\Exception\DriverException;
use QueryMule\Builder\Sql\MySql\Select;
use QueryMule\Query\Adapter\AdapterInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class PdoAdapter
 * @package QueryMule\Builder\Adapter
 */
class PdoAdapter implements AdapterInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * PdoAdapter constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param array $cols
     * @param TableInterface|null $table
     * @return SelectInterface
     */
    public function select(array $cols = [],TableInterface $table = null) : SelectInterface
    {
        return new Select($cols, $table);
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

        if (!$query->execute($sql->parameters())) {
            throw new DriverException('PDO error code: ' . $this->pdo->errorCode());
        }

        return $query;
    }
}












