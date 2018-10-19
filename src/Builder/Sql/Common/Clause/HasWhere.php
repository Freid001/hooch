<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnInterface;

/**
 * Trait HasWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhere
{
    use Common;

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return $this
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        $sql = new Sql();
        $column = $this->accent()->append($column, '.');
        $operator = !is_null($logical) ? $logical->getOperator() : null;

        if (!empty($this->query()->get(Sql::WHERE))) {
            if ($operator != Sql:: OR) {
                $operator = Sql:: AND;
            }
        }

        if (empty($this->query()->get(Sql::WHERE)) && !in_array($operator, [Sql:: AND, Sql:: OR])) {
            $sql->append($this->whereJoin())->appendIf($this->logical()->getNested(), Sql::SQL_BRACKET_OPEN);
        }

        if (!empty($this->query()->get(Sql::WHERE))) {
            if ($operator != Sql::OR) {
                if ($this->logical()->getNested()) {
                    $logical->setNested(true)->and($column, $comparison, $logical);
                    $this->logical()->setNested(false);
                } else {
                    $logical->and($column, $comparison, $logical);
                }
            }
        }else {
            $sql->append($column);
        }

        $this->query()->add($this->whereJoin(),$sql->appendIf(!is_null($comparison),$comparison)
                                                   ->appendIf(!is_null($logical),$logical));

        return $this;
    }

    /**
     * @return string
     */
    private function whereJoin()
    {
        if ($this instanceof OnInterface) {
            return Sql::JOIN;
        } else {
            return Sql::WHERE;
        }
    }
}
