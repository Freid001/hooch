<?php

namespace QueryMule\Query\Sql;

/**
 * Class Sql
 * @package QueryMule\Query\Sql
 */
class Sql
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Sql constructor.
     * @param string $sql
     * @param array $parameters
     */
    public function __construct($sql, array $parameters = [])
    {
        $this->sql = $sql;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function sql()
    {
        return $this->sql;
    }

    /**
     * @return array
     */
    public function parameters() : array
    {
        return $this->parameters;
    }
}
