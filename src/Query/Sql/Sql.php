<?php

namespace QueryMule\Query\Sql;

use QueryMule\Query\QueryBuilderInterface;

/**
 * Class Sql
 * @package QueryMule\Query\Sql
 */
class Sql
{
    const ALL = 'ALL';
    const AND = 'AND';
    const ANY = 'ANY';
    const AS = 'AS';
    const ASC = 'ASC';
    const BETWEEN = 'BETWEEN';
    const BY = 'BY';
    const COLS = 'COL';
    const COUNT = 'COUNT';
    const DELETE = 'DELETE';
    const DESC = 'DESC';
    const DISTINCT = 'DISTINCT';
    const EXISTS = 'EXISTS';
    const FETCH = 'fetch';
    const FETCH_ALL = 'fetchAll';
    const FETCH_COLUMN = 'fetchColumn';
    const FETCH_COLUMN_COUNT = 'columnCount';
    const FETCH_COLUMN_META = 'getColumnMeta';
    const FETCH_ROW_COUNT = 'rowCount';
    const FROM = 'FROM';
    const GROUP = 'GROUP BY';
    const HAVING = 'HAVING';
    const JOIN = 'JOIN';
    const JOIN_LEFT = 'LEFT JOIN';
    const JOIN_RIGHT = 'LEFT RIGHT';
    const INSERT = 'INSERT';
    const IN = 'IN';
    const INTO = 'INTO';
    const LIMIT = 'LIMIT';
    const NOT = 'NOT';
    const OFFSET = 'OFFSET';
    const OR = 'OR';
    const ORDER = 'ORDER';
    const ON = 'ON';
    const SET = 'SET';
    const SELECT = 'SELECT';
    const SQL_EQUAL = '=';
    const SQL_LIKE = 'LIKE';
    const SQL_GREATER_THAN = '>';
    const SQL_LESS_THAN = '<';
    const SQL_STAR = '*';
    const SQL_SPACE = ' ';
    const SQL_BRACKET_OPEN = '(';
    const SQL_BRACKET_CLOSE = ')';
    const SQL_QUESTION_MARK = '?';
    const SOME = 'SOME';
    const TABLE = 'TABLE';
    const UNION = 'UNION';
    const UPDATE = 'UPDATE';
    const VALUES = 'VALUES';
    const WHERE = 'WHERE';

    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Sql constructor.
     * @param null $sql
     * @param array $parameters
     * @param bool $space
     */
    public function __construct($sql = null, array $parameters = [], $space = true)
    {
        $this->append($sql,$parameters,$space);
    }

    /**
     * @param $sql
     * @param array $parameters
     * @param bool $space
     * @return $this
     */
    public function append($sql, array $parameters = [], $space = true)
    {
        if ($sql instanceof QueryBuilderInterface) {
            $this->append(
                $sql->build()->sql(),
                $sql->build()->parameters(),
                $space
            );

            return $this;
        }

        if ($sql instanceof Sql) {
            $this->append(
                $sql->sql(),
                $sql->parameters(),
                $space
            );

            return $this;
        }

        if (!empty($sql)) {
            $this->sql .= $sql;

            if($space){
                $this->sql .= Sql::SQL_SPACE;
            }
        }

        if (!empty($parameters)) {
            $this->parameters = array_merge($this->parameters, $parameters);
        }

        return $this;
    }

    /**
     * @param $condition
     * @param $sql
     * @param array $parameters
     * @param bool $space
     * @return $this
     */
    public function appendIf($condition, $sql, array $parameters = [], $space = true)
    {
        if ($condition) {
            $this->append($sql, $parameters, $space);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function sql()
    {
        return $this->sql;
    }
}
