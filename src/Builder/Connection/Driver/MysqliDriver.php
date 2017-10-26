<?php

namespace QueryMule\Builder\Connection\Driver;

use Psr\Log\LoggerInterface;
use QueryMule\Builder\Exception\DriverException;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Builder\Sql\Mysql\Select;
use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MysqliDriver constructor.
     * @param \mysqli $mysqli
     * @param LoggerInterface $logger
     */
    public function __construct(\mysqli $mysqli, LoggerInterface $logger)
    {
        $this->mysqli = $mysqli;
        $this->logger = $logger;
    }

    public function filter() : FilterInterface
    {
        // TODO: Implement filter() method.
    }

    /**
     * @param array $cols
     * @param RepositoryInterface|null $repository
     * @return SelectInterface
     */
    public function select(array $cols = [],RepositoryInterface $repository = null) : SelectInterface
    {
        return new Select($cols, $repository);
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
        $time = microtime(true);

        $this->logger->info('Executing query',[
            'query'         => $sql->sql(),
            'parameters'    => $sql->parameters(),
            'driver'        => self::DRIVER_MYSQL
        ]);

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
            $this->logger->critical("Mysqli error code: " . $this->mysqli->connect_errno,[
                'query'     => $sql->sql(),
                'message'   => $this->mysqli->error
            ]);

            return false;
        }

        $this->logger->info("Query successfully executed",[
            'query'             => $sql->sql(),
            'execution_time'    => round(microtime(true) - $time,3) . "s"
        ]);

        return $query->get_result();
    }
}
