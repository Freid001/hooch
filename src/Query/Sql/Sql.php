<?php

declare(strict_types=1);

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
    const DUPLICATE  = 'DUPLICATE';
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
    const JOIN_RIGHT = 'RIGHT JOIN';
    const JOIN_INNER = 'INNER JOIN';
    const JOIN_FULL_OUTER = 'FULL OUTER JOIN';
    const KEY = 'KEY';
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
    private $string;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Sql constructor.
     * @param string|null $string
     * @param array $parameters
     * @param bool $space
     */
    public function __construct(?string $string = null, array $parameters = [], bool $space = true)
    {
        $this->append($string, $parameters, $space);
    }

    /**
     * @param $append
     * @param array $parameters
     * @param bool $trailingSpace
     * @return Sql
     */
    public function append($append, array $parameters = [], $trailingSpace = true): Sql
    {
        if ($append instanceof QueryBuilderInterface) {
            $this->appendQueryBuilder($append, $trailingSpace);
        }

        if ($append instanceof Sql) {
            $this->appendSql($append, $trailingSpace);
        }

        if (is_string($append)) {
            $this->appendString($append, $trailingSpace);
        }

        if (is_integer($append)) {
            $this->appendInt($append, $trailingSpace);
        }

        if (!empty($parameters)) {
            $this->parameters = array_merge($this->parameters, $parameters);
        }

        return $this;
    }

    /**
     * @param QueryBuilderInterface $queryBuilder
     * @param bool $trailingSpace
     * @return Sql
     */
    public function appendQueryBuilder(QueryBuilderInterface $queryBuilder, bool $trailingSpace = true): Sql
    {
        $this->append(
            $queryBuilder->build()->string(),
            $queryBuilder->build()->parameters(),
            $trailingSpace
        );

        return $this;
    }

    /**
     * @return string|null
     */
    public function string(): ?string
    {
        return $this->string;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->string = '';
        $this->parameters = [];
    }

    /**
     * @param Sql $sql
     * @param bool $trailingSpace
     * @return Sql
     */
    public function appendSql(Sql $sql, bool $trailingSpace = true): Sql
    {
        $this->append(
            $sql->string(),
            $sql->parameters(),
            $trailingSpace
        );

        return $this;
    }

    /**
     * @param String $string
     * @param bool $trailingSpace
     * @return Sql
     */
    public function appendString(String $string, bool $trailingSpace = true): Sql
    {
        $this->string .= $string;

        if ($trailingSpace) {
            $this->string .= Sql::SQL_SPACE;
        }

        return $this;
    }

    /**
     * @param Int $int
     * @param bool $trailingSpace
     * @return Sql
     */
    public function appendInt(Int $int, bool $trailingSpace = true): Sql
    {
        $this->appendString((string)$int, $trailingSpace);

        return $this;
    }

    /**
     * @param $condition
     * @param $sql
     * @param array $parameters
     * @param bool $space
     * @return Sql
     */
    public function ifThenAppend($condition, $sql, array $parameters = [], $space = true): Sql
    {
        if ($condition) {
            $this->append($sql, $parameters, $space);
        }

        return $this;
    }
}
