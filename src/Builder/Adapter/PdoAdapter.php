<?php

namespace QueryMule\Builder\Adapter;

use QueryMule\Builder\Exception\SqlException;
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
     * @return \PDOStatement
     * @throws SqlException
     */
    public function execute(Sql $sql)
    {
        $query = $this->pdo->query($sql->sql());

        if (!$query) {
            throw new SqlException('PDO error code: ' . $this->pdo->errorCode());
        }

        $query->execute($sql->parameters());

        return $query;
    }
}












