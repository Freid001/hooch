<?php

namespace QueryMule\Query\Sql;

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
    const BETWEEN = 'BETWEEN';
    const BY = 'BY';
    const COLS = 'COL';
    const COUNT = 'COUNT';
    const DELETE = 'DELETE';
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
    const JOIN_LET = 'LEFT JOIN';
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
    public function parameters(): array
    {
        return $this->parameters;
    }
}
