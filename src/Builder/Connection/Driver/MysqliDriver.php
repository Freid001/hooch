<?php

namespace QueryMule\Builder\Connection\Driver;

use QueryMule\Builder\Exception\DriverException;
use QueryMule\Builder\Sql\Mysql\Select;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class MysqliDriver
 * @package QueryMule\Builder\Connection\Driver
 */
class MysqliDriver implements DriverInterface
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

        $parameters = [0 => ''];
        foreach($sql->parameters() as $parameter){
            switch(gettype($parameter)){
                case 'integer':
                    $parameters[0] .= 'i';
                    break;

                case 'float':
                    $parameters[0] .= 'd';
                    break;

                case 'string':
                    $parameters[0] .= 's';
                    break;

                default:
                    $parameters[0] .= 'b';
                    break;
            }

            $parameters[] = &$parameter;
        }

        call_user_func_array([$query, 'bind_param'], $parameters);

        if (!$query->execute()) {
            throw new DriverException("Mysqli err no: " . $this->mysqli->connect_errno);
        }

        return $query->get_result();
    }
}












