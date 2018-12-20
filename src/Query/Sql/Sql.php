<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql;


use phpDocumentor\Reflection\Types\Integer;
use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

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
    const JOIN_RIGHT = 'RIGHT JOIN';
    const JOIN_INNER = 'INNER JOIN';
    const JOIN_FULL_OUTER = 'FULL OUTER JOIN';
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
     * @param string|null $sql
     * @param array $parameters
     * @param bool $space
     */
    public function __construct(?string $sql = null, array $parameters = [], bool $space = true)
    {
        $this->append($sql, $parameters, $space);
    }

    /**
     * @param $append
     * @param array $parameters
     * @param bool $trailingSpace
     * @return Sql
     */
    public function append($append, array $parameters = [], $trailingSpace = true): Sql
    {
        if (!empty($parameters)) {
            $this->parameters = array_merge($this->parameters, $parameters);
        }

        if ($append instanceof QueryBuilderInterface) {
            return $this->appendQueryBuilder($append, $trailingSpace);
        }

        if ($append instanceof Sql) {
            return $this->appendSql($append, $trailingSpace);
        }

        if (is_string($append)) {
            return $this->appendString($append, $trailingSpace);
        }

        if (is_integer($append)) {
            return $this->appendInt($append, $trailingSpace);
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
            $queryBuilder->build()->sql(),
            $queryBuilder->build()->parameters(),
            $trailingSpace
        );

        return $this;
    }

    /**
     * @return string|null
     */
    public function sql(): ?string
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

    /**
     * @param Sql $sql
     * @param bool $trailingSpace
     * @return Sql
     */
    public function appendSql(Sql $sql, bool $trailingSpace = true): Sql
    {
        $this->append(
            $sql->sql(),
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
        $this->sql .= $string;

        if ($trailingSpace) {
            $this->sql .= Sql::SQL_SPACE;
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
