<?php

namespace QueryMule\Builder\Adapter;

use QueryMule\Builder\Exception\DriverException;
use QueryMule\Builder\Exception\SqlException;
use QueryMule\Builder\Sql\MySql\Select;
use QueryMule\Query\Adapter\AdapterInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class MysqliAdapter
 * @package QueryMule\Builder\Adapter
 */
class MysqliAdapter implements AdapterInterface
{
    /**
     * @var \mysqli
     */
    private $mysqli;

    /**
     * MysqliAdapter constructor.
     * @param \mysqli $mysqli
     */
    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
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
     * @throws DriverException
     */
    public function fetch(Sql $sql)
    {
        return $this->execute($sql)->fetch_assoc();
    }

    /**
     * @param Sql $sql
     * @return array
     * @throws DriverException
     */
    public function fetchAll(Sql $sql)
    {
        return $this->execute($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param Sql $sql
     * @return bool|\mysqli_result
     * @throws DriverException
     */
    private function execute(Sql $sql)
    {
        $query = $this->mysqli->prepare($sql->sql());

        foreach($sql->parameters() as $parameter) {
            $query->bind_param('s',$parameter);
        }

        if (!$query->execute()) {
            throw new DriverException("Mysqli err no: " . $this->mysqli->connect_errno);
        }

        return $query->get_result();
    }
}












