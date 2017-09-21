<?php

namespace QueryMule\Query\Sql;

use QueryMule\Query\Sql\Sql;

/**
 * Class Query
 * @package QueryMule\Query\Sql
 */
trait Query
{
    /**
     * @var array
     */
    private $sql = [];

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param $type
     * @param Sql $sql
     */
    private function queryAdd($type, Sql $sql)
    {
        $this->sql[$type] = !empty($this->sql[$type]) ? $this->sql[$type] . ' ' . $sql->sql() : $sql;

        foreach($sql->parameters() as $key => $parameter){
            $this->parameters[$type] = $parameter;
        }
    }

    /**
     * @param $type
     * @return string|null
     */
    private function queryGet($type)
    {
        return !empty($this->sql[$type]) ? $this->sql[$type] : null;
    }

    /**
     * @param array $buildOrder
     * @return string
     */
    private function queryBuild(array $buildOrder)
    {
        foreach($buildOrder as $type){
            if(!empty($this->sql[$type])) {

                $parameters = [];
                if(!empty($this->parameters[$type])) {
                    foreach ($this->parameters[$type] as $parameter) {
                        $parameters[] = $parameter;
                    }
                }

                var_dump($type);
            }
        }
    }
}